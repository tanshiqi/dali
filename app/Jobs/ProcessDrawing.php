<?php

namespace App\Jobs;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class ProcessDrawing implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public Task $task)
    {
        $this->onQueue('baidu');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $task_id = $this->sendTask();
        while ($this->task->result == null) {
            sleep(1);
            $this->getResult();
        }
        logger('任务完成，task_id: '.$this->task->task_id);
        sleep(1);
    }

    public function sendTask()
    {
        $url = 'https://aip.baidubce.com/rpc/2.0/ernievilg/v1/txt2imgv2?access_token='.cache('token');
        $params = [
            'prompt' => $this->task->prompt,
            'width' => $this->task->width,
            'height' => $this->task->height,
            'change_degree' => $this->task->change_degree,
            'url' => $this->task->reference,
        ];

        $response = Http::post($url, $params);

        if ($response->ok()) {
            if (array_key_exists('error_code', $response->json())) {
                // 写入错误信息
                $err_task_id = 'err_'.time();
                logger()->error([
                    'prompt' => $this->task->prompt,
                    'task_id' => $err_task_id,
                    'response' => $response->json(),
                ]);
                $task_id = $err_task_id;
            } else {
                $task_id = data_get($response->json(), 'data.task_id');
            }
            logger('任务已创建，task_id: '.$task_id);

            $this->task->update([
                'task_id' => $task_id,
                'result' => str_starts_with($task_id, 'err_') ? 'block.png' : null,
                'error' => str_starts_with($task_id, 'err_') ? data_get($response->json(), 'error_msg') : null,
            ]);
        }

        return $task_id;
    }

    public function getResult()
    {
        $url = 'https://aip.baidubce.com/rpc/2.0/ernievilg/v1/getImgv2?access_token='.cache('token');
        $params = [
            'task_id' => $this->task->task_id,
        ];

        $response = Http::post($url, $params);

        if ($response->ok()) {
            try {
                if (array_key_exists('error_code', $response->json()) || data_get($response->json(), 'data.task_status') == 'FAILED' || data_get($response->json(), 'data.sub_task_result_list.0.final_image_list.0.img_approve_conclusion') == 'block') {
                    logger()->error([
                        'prompt' => $this->task->prompt,
                        'task_id' => $this->task->task_id,
                        'response' => $response->json(),
                    ]);
                    // 写入错误图片
                    $this->task->update([
                        'result' => 'block.png',
                    ]);
                }

                if (data_get($response->json(), 'data.sub_task_result_list.0.final_image_list.0.img_approve_conclusion') == 'pass') {
                    $originImg = data_get($response->json(), 'data.sub_task_result_list.0.final_image_list.0.img_url');

                    // 转存到七牛云
                    $disk = Storage::disk('qiniu');
                    $savedResponse = $disk->getAdapter()->getBucketManager()->fetch($originImg, $disk->getAdapter()->getBucket());
                    $savedImage = $savedResponse[0]['key'];

                    // 写入正式图片
                    $this->task->update([
                        'result' => $savedImage,
                    ]);
                }
            } catch (\Exception $e) {
                logger()->error([
                    'prompt' => $this->task->prompt,
                    'task_id' => $this->task->task_id,
                    'response' => $response->json(),
                ]);
            }
        }
    }
}
