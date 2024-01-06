<?php

namespace App;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class Midjourney
{
    public static function refining($prompt, $id, $index)
    {
        $task = Task::find($id);

        $url = env('MJ_PROXY').'/mj/submit/simple-change';
        $headers = [
            'mj-api-secret' => env('MJ_APIKEY'),
        ];
        $params = [
            'content' => $task->task_id.' U'.$index,
            'notifyHook' => env('MJ_WEBHOOK'),
        ];

        $response = Http::withHeaders($headers)->post($url, $params);

        if ($response->ok()) {
            try {
                $subtask_id = data_get($response->json(), 'result');
                $refiningTask = Task::create([
                    'user_id' => auth()->id(),
                    'aiprovider' => 'Midjourney',
                    'prompt' => 'Refining #'.$index.' '.$prompt,
                    'task_id' => $subtask_id,
                    'params' => [
                        'main_task' => $task->id,
                        'main_task_id' => $task->task_id,
                        'refining' => $index,
                    ],
                ]);
                info('Midjourney Refining 任务已创建，task_id: '.$subtask_id);

                // 任务已经重复
                if (data_get($response->json(), 'code') == 21) {
                    $oldTask = Task::where('task_id', $subtask_id)->first();
                    $refiningTask->update([
                        'result' => $oldTask->result,
                    ]);
                }

            } catch (\Exception $e) {
                logger()->error([
                    'prompt' => $task->prompt,
                    'task_id' => $task->task_id,
                    'response' => $response->json(),
                ]);
            }
        }
    }

    public static function webhook(Request $request)
    {
        // logger()->notice($request->all());
        if (data_get($request, 'status') == 'SUCCESS') {
            $task_id = data_get($request, 'id');
            info([
                'message' => 'Midjourney webhook success',
                'task_id' => $task_id,
            ]);
            $imgOrigin = data_get($request, 'imageUrl');
            $tasks = Task::where('task_id', $task_id); // 可能有多条记录

            try {
                // 存储到七牛
                $fetchUrl = str_replace('https://', 'https://wsrv.nl/?url=', $imgOrigin);
                $savedImage = Qiniu::fetch($fetchUrl);

                $tasks->update([
                    'result' => $savedImage['key'],
                    'width' => $savedImage['width'],
                    'height' => $savedImage['height'],
                ]);

                info('Midjourney 任务完成，task_id: '.$task_id);

            } catch (\Exception $e) {
                logger()->error([
                    'message' => 'Midjourney webhook error',
                    'task_id' => $task_id,
                    'request' => $request->json(),
                ]);
            }
        }

        return response()->json([
            'data' => 'success',
        ]);
    }
}
