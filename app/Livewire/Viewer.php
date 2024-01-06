<?php

namespace App\Livewire;

use App\Midjourney;
use LivewireUI\Modal\ModalComponent;

class Viewer extends ModalComponent
{
    public $task;

    public static function destroyOnClose(): bool
    {
        return false;
    }

    public function refining($index)
    {
        // sleep(300);
        Midjourney::refining($this->task['prompt'], $this->task['id'], $index);
        $this->closeModalWithEvents(['refrshScrollTop']);
    }

    public function render()
    {
        return view('livewire.viewer');
    }
}
