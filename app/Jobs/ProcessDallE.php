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

class ProcessDallE implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public Task $task)
    {
        $this->onQueue('dalle');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->task->update([
            'task_id' => 'dalle'.uniqid(),
        ]);
        info('DALL-E 任务已创建，task_id: '.$this->task->task_id);

        $url = 'https://api.openai.com/v1/images/generations';
        $headers = [
            'Authorization' => 'Bearer '.env('OPENAI_API_KEY'),
        ];
        $params = [
            'prompt' => $this->task->prompt,
            'size' => $this->task->width.'x'.$this->task->height,
            'model' => 'dall-e-3',
            'n' => 1,
            'response_format' => 'b64_json',
        ];

        $response = Http::timeout(60)->withHeaders($headers)->post($url, $params);

        if ($response->ok()) {
            try {
                $imageBase64 = $response->json('data.0.b64_json');
                // 写文件到七牛并审核
                $savedImage = Qiniu::put64($imageBase64);

                $this->task->update([
                    'result' => $savedImage['key'],
                ]);
                info('DALL-E 任务完成，task_id: '.$this->task->task_id);

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
