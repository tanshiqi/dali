<?php

namespace App\Livewire;

use App\Midjourney;
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
        $this->closeModalWithEvents(['refrshScrollTop']);
    }

    public function render()
    {
        return view('livewire.viewer');
    }
}
