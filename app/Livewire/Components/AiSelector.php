<?php

namespace App\Livewire\Components;

use Livewire\Attributes\Modelable;
use Livewire\Component;

class AiSelector extends Component
{
    #[Modelable]
    public $value = '';

    public function updatedValue()
    {
        logger('updated');
    }

    public function render()
    {
        return view('livewire.components.ai-selector');
    }
}
