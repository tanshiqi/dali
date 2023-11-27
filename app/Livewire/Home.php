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
        $task_id = $this->sendTask($this->prompt, $this->width, $this->height, $this->change_degree, $this->url);

        $task = Task::create([
            'user_id' => 1,
            'prompt' => $this->prompt,
            'width' => $this->width,
            'height' => $this->height,
            'url' => $this->url,
            'change_degree' => $this->change_degree,
            'task_id' => $task_id,
            // 如果是错误的任务，直接返回错误图片
            'result' => str_starts_with($task_id, 'err_') ? 'http://ledoteaching.cdn.pinweb.io/dali/20231126_9qDtzR.png' : null,
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

    protected function sendTask($prompt, $width, $height, $change_degree, $reference = '')
    {
        $url = 'https://aip.baidubce.com/rpc/2.0/ernievilg/v1/txt2imgv2?access_token='.cache('token');
        $params = [
            'prompt' => $prompt,
            'width' => $width,
            'height' => $height,
            'change_degree' => $change_degree,
            'url' => $reference,
        ];

        $response = Http::post($url, $params);

        if ($response->ok()) {
            if (array_key_exists('error_code', $response->json())) {
                $err_task_id = 'err_'.time();
                logger()->error([
                    'prompt' => $prompt,
                    'task_id' => $err_task_id,
                    'response' => $response->json(),
                ]);

                return $err_task_id;
            }

            return data_get($response->json(), 'data.task_id');
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
            if (array_key_exists('error_code', $response->json()) || data_get($response->json(), 'data.task_status') == 'FAILED') {
                logger()->error([
                    'task_id' => $task_id,
                    'response' => $response->json(),
                ]);
                $this->getFailed($task_id);
            }

            if (data_get($response->json(), 'data.task_status') == 'SUCCESS') {
                $originImg = data_get($response->json(), 'data.sub_task_result_list.0.final_image_list.0.img_url');

                // 转存到七牛云
                $disk = Storage::disk('qiniu');
                $savedResponse = $disk->getAdapter()->getBucketManager()->fetch($originImg, $disk->getAdapter()->getBucket());
                $savedImage = $disk->getAdapter()->getUrl($savedResponse[0]['key']);

                Task::where('task_id', $task_id)->update([
                    'result' => $savedImage,
                ]);
            }

            return;
        }

    }

    protected function getFailed($task_id)
    {
        // 写入错误图片
        Task::where('task_id', $task_id)->update([
            'result' => 'http://ledoteaching.cdn.pinweb.io/dali/20231126_9qDtzR.png',
        ]);
    }
}
