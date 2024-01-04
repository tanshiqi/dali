<?php

namespace App\Livewire;

use App\Models\Task;
use LivewireUI\Modal\ModalComponent;

class Viewer extends ModalComponent
{
    public Task $task;

    public function render()
    {
        return view('livewire.viewer');
    }
}
