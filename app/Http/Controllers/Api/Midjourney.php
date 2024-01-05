<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class Midjourney extends Controller
{
    /**
     * Update the specified resource in storage.
     */
    public function webhook(Request $request)
    {
        if (data_get($request, 'status') == 'SUCCESS') {
            $imgOrigin = data_get($request, 'imageUrl');
            $task_id = data_get($request, 'id');
            $task = Task::where('task_id', $task_id)->first();

            try {
                // 存储到七牛
                $fetchUrl = str_replace('https://', 'https://wsrv.nl/?url=', $imgOrigin);
                $disk = Storage::disk('qiniu');
                $savedResponse = $disk->getAdapter()->getBucketManager()->fetch($fetchUrl, $disk->getAdapter()->getBucket());
                $savedImage = $savedResponse[0]['key'];

                $task->update([
                    'result' => $savedImage,
                ]);
                info('Midjourney 任务完成，task_id: '.$task->task_id);

            } catch (\Exception $e) {
                logger()->error([
                    'message' => 'Midjourney webhook error',
                    'prompt' => $task->prompt,
                    'task_id' => $task->task_id,
                    'request' => $request->json(),
                ]);
            }

        }

        return response()->json([
            'data' => 'success',
        ]);
    }
}
