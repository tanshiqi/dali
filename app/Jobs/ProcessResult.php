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

class ProcessResult implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    // 可以尝试任务的次数
    public $tries = 10;

    /**
     * Create a new job instance.
     */
    public function __construct(public $task_id)
    {
        $this->onQueue('result');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $url = 'https://aip.baidubce.com/rpc/2.0/ernievilg/v1/getImgv2?access_token='.cache('token');
        $params = [
            'task_id' => $this->task_id,
        ];

        $response = Http::post($url, $params);

        if ($response->ok()) {
            if (array_key_exists('error_code', $response->json()) || data_get($response->json(), 'data.task_status') == 'FAILED' || data_get($response->json(), 'data.sub_task_result_list.0.final_image_list.0.img_approve_conclusion') == 'block') {
                logger()->error([
                    'task_id' => $this->task_id,
                    'response' => $response->json(),
                ]);
                $this->getFailed($this->task_id, data_get($response->json(), 'data.sub_task_result_list.0.sub_task_error_code'));
            }

            if (data_get($response->json(), 'data.sub_task_result_list.0.final_image_list.0.img_approve_conclusion') == 'pass') {
                $originImg = data_get($response->json(), 'data.sub_task_result_list.0.final_image_list.0.img_url');

                // 转存到七牛云
                $disk = Storage::disk('qiniu');
                $savedResponse = $disk->getAdapter()->getBucketManager()->fetch($originImg, $disk->getAdapter()->getBucket());
                $savedImage = $savedResponse[0]['key'];

                Task::where('task_id', $this->task_id)->update([
                    'result' => $savedImage,
                ]);
            } else {
                // 重新发布任务进入队列
                $this->release(2);
            }
        }

    }

    protected function getFailed($task_id, $error = null)
    {
        // 写入错误图片
        Task::where('task_id', $task_id)->update([
            'result' => 'block.png',
            'error' => $error,
        ]);
    }
}
