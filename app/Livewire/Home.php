<?php

namespace App\Livewire;

use App\Jobs\ProcessDallE;
use App\Jobs\ProcessDrawing;
use App\Jobs\ProcessMidjourney;
use App\Jobs\ProcessStableDiffusion;
use App\Models\Task;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

class Home extends Component
{
    use WithFileUploads;

    public $prompt = '';

    public $size = '512 x 512';

    public $width = 512;

    public $height = 512;

    public $url = '';

    public $photo;

    public $change_degree = 1;

    public $amount = 5;

    public $shortid;

    public $aiprovider = 'Stable Diffusion'; // AI模型

    //stable diffusion 专用

    public $negative_prompt = '';

    public $sampler_name = 'DPM++ 3M SDE Karras';

    public $steps = 20;

    public $cfg_scale = 7;

    public $prompt_for_face = '';

    public function mount()
    {
        if (auth()->id() != $this->shortid) {
            auth()->logout();
        }
    }

    #[On('refrshScrollTop')]
    public function refrshScrollTop()
    {
        $this->js('
            $wire.$refresh();
            window.scrollTo({top: 0, behavior: "smooth"})
        ');
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
        return $this->sendTask($this->aiprovider, $this->prompt, $this->width, $this->height, $this->change_degree, $this->url);
    }

    public function loadmore()
    {
        $this->amount += 5;
    }

    public function render()
    {
        $tasks = Task::where('user_id', auth()->id())->latest()->take($this->amount)->get();

        return view('livewire.home', [
            'tasks' => $tasks,
        ]);
    }

    protected function sendTask($aiprovider, $prompt, $width, $height, $change_degree, $reference = '')
    {
        $task = Task::create([
            'user_id' => auth()->id(),
            'aiprovider' => $aiprovider,
            'prompt' => $prompt,
            'width' => $width,
            'height' => $height,
            'url' => $reference,
            'change_degree' => $change_degree,
        ]);

        if ($aiprovider == 'Stable Diffusion') {
            $task->update([
                'params' => [
                    'negative_prompt' => config('dali.default_negative_prompt').$this->negative_prompt,
                    'sampler_name' => $this->sampler_name,
                    'steps' => $this->steps,
                    'cfg_scale' => $this->cfg_scale,
                    'alwayson_scripts' => [
                        'face editor ex' => [
                            'args' => [
                                [
                                    'prompt_for_face' => $this->prompt_for_face,
                                ],
                            ],
                        ],
                    ],
                ],
            ]);
        }

        switch ($this->aiprovider) {
            case 'Baidu AI':
                ProcessDrawing::dispatch($task);
                break;

            case 'DALL-E':
                ProcessDallE::dispatch($task);
                break;

            case 'Midjourney':
                ProcessMidjourney::dispatch($task);
                break;

            default:
                ProcessStableDiffusion::dispatch($task);
                break;
        }

        sleep(1);
        $this->js('
            window.scrollTo({top: 0, behavior: "smooth"})
        ');
    }

    public function getResult($task_id)
    {
        if ($task_id) {
            return Task::where('task_id', $task_id)->first();
        }
    }

    public function quit()
    {
        auth()->logout();

        $this->redirect('/');
    }
}
