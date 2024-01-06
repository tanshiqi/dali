<?php

namespace App\Jobs;

use App\Models\Task;
use App\Qiniu;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class ProcessStableDiffusion implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public Task $task)
    {
        $this->onQueue('stablediffusion');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->task->update([
            'task_id' => 'sd'.uniqid(),
        ]);
        $url = env('SD').'/sdapi/v1/txt2img';
        $params = array_merge([
            'prompt' => $this->task->prompt,
            'width' => $this->task->width,
            'height' => $this->task->height,
            // 'change_degree' => $this->task->change_degree,
            // 'url' => $this->task->reference,
        ], $this->task->params);

        // logger($params);

        $response = Http::timeout(60)->post($url, $params);

        if ($response->ok()) {
            try {
                $imageBase64 = data_get($response->json(), 'images.0');

                // 写文件到七牛并审核
                $savedImage = Qiniu::put64($imageBase64);

                $this->task->update([
                    'result' => $savedImage['key'],
                ]);
                info('Stable Diffusion 任务完成，task_id: '.$this->task->task_id);

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
