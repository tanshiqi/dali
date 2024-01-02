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
        $url = 'http://localhost:6006/sdapi/v1/txt2img';
        $params = [
            'prompt' => $this->task->prompt,
            'width' => $this->task->width,
            'height' => $this->task->height,
            // 'change_degree' => $this->task->change_degree,
            // 'url' => $this->task->reference,
            'restore_faces' => true, // 脸部修复
        ];

        $response = Http::post($url, $params);

        if ($response->ok()) {
            try {
                $imageBase64 = data_get($response->json(), 'images.0');
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
                        'result' => 'dali/20231126_9qDtzR.png',
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
