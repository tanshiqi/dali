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

class ProcessTaskCheck implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public Task $task)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        switch ($this->task->aiprovider) {
            case 'Midjourney':
                $this->handleMidjourneyTask($this->task);
                break;
            default:
                // code...
                break;
        }
    }

    public function handleMidjourneyTask($task)
    {
        $url = env('MJ_PROXY').'/mj/task/'.$task->task_id.'/fetch';
        $headers = [
            'mj-api-secret' => env('MJ_APIKEY'),
        ];
        $response = Http::withHeaders($headers)->get($url);
        if ($response->ok()) {
            if ($response->json('status') == 'SUCCESS') {
                $fetchUrl = str_replace('https://', 'https://wsrv.nl/?url=', $response->json('imageUrl'));
                $savedImage = Qiniu::fetch($fetchUrl);

                $task->update([
                    'result' => $savedImage['key'],
                    'width' => $savedImage['width'],
                    'height' => $savedImage['height'],
                ]);
            }
            if ($response->json('status') == 'FAILURE') {
                //失败，超时
                $task->update([
                    'result' => 'block.png',
                    'width' => 400,
                    'height' => 400,
                ]);
                logger()->error([
                    'message' => 'Midjourney 任务失败',
                    'task_id' => $task->task_id,
                    'response' => $response->json(),
                ]);
            }
        }
    }
}
