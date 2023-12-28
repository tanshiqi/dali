<?php

namespace App\Livewire;

use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class Home extends Component
{
    use WithFileUploads;

    public $prompt = '';

    public $size = '1024 x 1024';

    public $width = 1024;

    public $height = 1024;

    public $url = '';

    public $photo;

    public $change_degree = 1;

    public $amount = 5;

    public $ulid;

    public function mount()
    {
        if (session()->get('ulid') != $this->ulid) {
            if (User::find($this->ulid)) {
                session()->put('ulid', $this->ulid);
            } else {
                // session()->forget('ulid');

                return redirect('/');
            }
        }
        // dump(session()->get('ulid'));
    }

    public function updatedPhoto()
    {

        // 生成一个唯一的、随机的名字
        $hash = 'ref/'.$this->photo->hashName();
        // 从tmp文件夹移动到ref文件夹，用move代替store，速度更快，因为已经在远程的网盘里了
        Storage::disk('qiniu')->move($this->photo->path(), $hash);
        $this->url = Storage::disk('qiniu')->url($hash);
        $this->dispatch('visible', true);
    }

    public function sizeChanged()
    {
        $size = explode(' x ', $this->size);

        $this->width = intval($size[0]);
        $this->height = intval($size[1]);
    }

    public function save()
    {
        return $this->sendTask($this->prompt, $this->width, $this->height, $this->change_degree, $this->url);
    }

    public function loadmore()
    {
        $this->amount += 5;
    }

    public function render()
    {
        $tasks = Task::where('user_id', $this->ulid)->latest()->take($this->amount)->get();

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
                // 写入错误信息
                $err_task_id = 'err_'.time();
                logger()->error([
                    'prompt' => $prompt,
                    'task_id' => $err_task_id,
                    'response' => $response->json(),
                ]);
                $task_id = $err_task_id;
            } else {
                $task_id = data_get($response->json(), 'data.task_id');
            }

            Task::create([
                'user_id' => $this->ulid,
                'prompt' => $prompt,
                'width' => $width,
                'height' => $height,
                'url' => $reference,
                'change_degree' => $change_degree,
                'task_id' => $task_id,
                'result' => str_starts_with($task_id, 'err_') ? 'dali/20231126_9qDtzR.png' : null,
                'error' => str_starts_with($task_id, 'err_') ? data_get($response->json(), 'error_msg') : null,
            ]);

            return $task_id;
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
            if (array_key_exists('error_code', $response->json()) || data_get($response->json(), 'data.task_status') == 'FAILED' || data_get($response->json(), 'data.sub_task_result_list.0.final_image_list.0.img_approve_conclusion') == 'block') {
                logger()->error([
                    'task_id' => $task_id,
                    'response' => $response->json(),
                ]);
                $this->getFailed($task_id, data_get($response->json(), 'data.sub_task_result_list.0.sub_task_error_code'));
            }

            if (data_get($response->json(), 'data.sub_task_result_list.0.final_image_list.0.img_approve_conclusion') == 'pass') {
                $originImg = data_get($response->json(), 'data.sub_task_result_list.0.final_image_list.0.img_url');

                // 转存到七牛云
                $disk = Storage::disk('qiniu');
                $savedResponse = $disk->getAdapter()->getBucketManager()->fetch($originImg, $disk->getAdapter()->getBucket());
                $savedImage = $savedResponse[0]['key'];

                Task::where('task_id', $task_id)->update([
                    'result' => $savedImage,
                ]);
            }

            return;
        }

    }

    public function quit()
    {
        session()->forget('ulid');

        return redirect('/');
    }

    protected function getFailed($task_id, $error = null)
    {
        // 写入错误图片
        Task::where('task_id', $task_id)->update([
            'result' => 'dali/20231126_9qDtzR.png',
            'error' => $error,
        ]);
    }
}
