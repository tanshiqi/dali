<?php

namespace App\Livewire;

use App\Models\Task;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class Home extends Component
{
    public $prompt = '';

    public $size = '1024 x 1024';

    public $width = 1024;

    public $height = 1024;

    public $url = '';

    public $change_degree = 1;

    public function sizeChanged()
    {
        $size = explode(' x ', $this->size);

        $this->width = intval($size[0]);
        $this->height = intval($size[1]);
    }

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

    public function getResult($task_id)
    {
        $url = 'https://aip.baidubce.com/rpc/2.0/ernievilg/v1/getImgv2?access_token='.cache('token');
        $params = [
            'task_id' => $task_id,
        ];

        $response = Http::post($url, $params);

        if ($response->ok()) {
            if ($response->json()['data']['task_status'] == 'FAILED') {
                Task::where('task_id', $task_id)->update([
                    'result' => 'http://ledoteaching.cdn.pinweb.io/dali/20231125_wAatdB.png',
                ]);
            }

            if ($response->json()['data']['task_status'] == 'SUCCESS') {
                $originImg = $response->json()['data']['sub_task_result_list'][0]['final_image_list'][0]['img_url'];

                // 转存到七牛云
                $disk = Storage::disk('qiniu');
                $savedResponse = $disk->getAdapter()->getBucketManager()->fetch($originImg, $disk->getAdapter()->getBucket());
                $savedImage = $disk->getAdapter()->getUrl($savedResponse[0]['key']);

                Task::where('task_id', $task_id)->update([
                    'result' => $savedImage,
                ]);
            }

        }

    }
}
