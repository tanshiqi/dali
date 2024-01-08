<?php

namespace App\Livewire;

use App\Models\Gallery as GalleryModel;
use Livewire\Component;

class Gallery extends Component
{
    public function render()
    {

        return view('livewire.gallery', [
            'items' => GalleryModel::latest()->get(),
        ]);
    }
}
