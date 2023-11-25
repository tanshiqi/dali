<?php

namespace App\Livewire;

use App\Models\Task;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

class Home extends Component
{
    public $prompt = '';

    public $width = 1024;

    public $height = 1024;

    public $url = '';

    public $change_degree = 1;

    public function save()
    {
        $task_id = $this->sendTask($this->prompt, $this->width, $this->height);

        $task = Task::create([
            'user_id' => 1,
            'prompt' => $this->prompt,
            'width' => $this->width,
            'height' => $this->height,
            'url' => $this->url,
            'change_degree' => $this->change_degree,
            'task_id' => $task_id,
        ]);

        return $task;
    }

    public function render()
    {
        $tasks = Task::latest()->get();

        return view('livewire.home', [
            'tasks' => $tasks,
        ]);
    }

    public function getResult($task_id)
    {
        $url = 'https://aip.baidubce.com/rpc/2.0/ernievilg/v1/getImgv2?access_token='.cache('token');
        $params = [
            'task_id' => $task_id,
        ];

        $response = Http::post($url, $params);

        if ($response->ok() && $response->json()['data']['task_status'] == 'SUCCESS') {
            Task::where('task_id', $task_id)->update([
                'result' => $response->json()['data']['sub_task_result_list'][0]['final_image_list'][0]['img_url'],
            ]);
        }
    }

    protected function sendTask($prompt, $width, $height)
    {
        $url = 'https://aip.baidubce.com/rpc/2.0/ernievilg/v1/txt2imgv2?access_token='.cache('token');
        $params = [
            'prompt' => $prompt,
            'width' => $width,
            'height' => $height,
        ];

        $response = Http::post($url, $params);

        if ($response->ok()) {
            return $response->json()['data']['task_id'];
        }
    }
}
