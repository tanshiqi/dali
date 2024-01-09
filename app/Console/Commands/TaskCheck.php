<?php

namespace App\Console\Commands;

use App\Jobs\ProcessTaskCheck;
use App\Models\Task;
use Illuminate\Console\Command;

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
            ProcessTaskCheck::dispatch($task);
        }
    }
}
