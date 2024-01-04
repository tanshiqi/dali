<?php

namespace App\Jobs;

use App\Censor;
use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
                $imageBase64 = data_get($response->json(), 'data.0.b64_json');
                // 写文件到七牛
                $disk = Storage::disk('qiniu');
                $filename = Str::random(16).'.png';
                $disk->put($filename, base64_decode($imageBase64));

                // 审查图片
                if (Censor::censorImageViaBaidu(Storage::disk('qiniu')->url($filename))) {
                    $this->task->update([
                        'result' => $filename,
                    ]);
                } else {
                    $this->task->update([
                        'result' => 'block.png',
                    ]);
                }
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
