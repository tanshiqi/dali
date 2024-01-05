<?php

namespace App\Jobs;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class ProcessMidjourney implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public Task $task)
    {
        $this->onQueue('midjourney');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $url = 'http://sg.atan.io:8080/mj/submit/imagine';
        $headers = [
            'mj-api-secret' => env('MJ_APIKEY'),
        ];
        $params = [
            'prompt' => $this->task->prompt,
            'notifyHook' => env('MJ_WEBHOOK'),
        ];

        $response = Http::withHeaders($headers)->post($url, $params);

        if ($response->ok()) {
            try {
                $this->task->update([
                    'task_id' => data_get($response->json(), 'result'),
                ]);
                info('Midjourney 任务已创建，task_id: '.$this->task->task_id);

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
