<?php

namespace App\Livewire;

use App\Models\Task;
use Livewire\Component;

class Gallery extends Component
{
    public function render()
    {
        return view('livewire.gallery', [
            'gallery' => Task::latest()->get(),
        ]);
    }
}
