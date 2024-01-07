<?php

namespace App\Console\Commands;

use App\Models\Task;
use App\Qiniu;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class TaskCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'task:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '检查所有的任务，获取还没得到结果的任务，重新获取结果，建议每分钟一次';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tasks = Task::where('result', '')->orWhereNull('result')->get();
        foreach ($tasks as $task) {
            switch ($task->aiprovider) {
                case 'Midjourney':
                    $this->handleMidjourneyTask($task);
                    break;
                default:
                    // code...
                    break;
            }
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
