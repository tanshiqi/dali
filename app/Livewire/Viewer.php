<?php

namespace App\Livewire;

use App\Midjourney;
use App\Models\Gallery;
use App\Models\Task;
use LivewireUI\Modal\ModalComponent;

class Viewer extends ModalComponent
{
    public $item;

    public $upscale = false;

    public static function destroyOnClose(): bool
    {
        return false;
    }

    public function refining($index)
    {
        // sleep(300);
        Midjourney::refining($this->item['prompt'], $this->item['id'], $index);
        $this->dispatch('refrshScrollTop')->to(Home::class);
    }

    public function toggleFavorite($task_id)
    {
        $galleryExists = Gallery::where('task_id', $task_id)->exists();
        if ($galleryExists) {
            Gallery::where('task_id', $task_id)->delete();
        } else {
            $task = Task::select('task_id', 'aiprovider', 'prompt', 'result', 'width', 'height', 'params')->where('task_id', $task_id)->first()->toArray();
            Gallery::create($task);
        }
    }

    public function render()
    {
        return view('livewire.viewer', [
            'inGallery' => Gallery::where('task_id', $this->item['task_id'])->exists(),
        ]);
    }
}
