<?php

namespace App\Livewire;

use App\Models\Task;
use Livewire\Component;

class Gallery extends Component
{
    public function render()
    {

        return view('livewire.gallery', [
            'taskgroups' => Task::whereNotNull('result')->latest()->get()->groupBy(function ($item) {
                return $item->created_at->format('Y-m-d');
            }),
        ]);
    }
}
