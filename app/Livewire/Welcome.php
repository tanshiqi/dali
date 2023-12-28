<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Str;
use Livewire\Component;

class Welcome extends Component
{
    public function mount()
    {
        $ulid = session()->get('ulid');
        if ($ulid && User::find($ulid)) {
            return redirect('/'.$ulid);
        } else {
            // 创建新用户
            $user = User::create(['name' => Str::random(12)]);
            session()->put('ulid', $user->id);

            return redirect('/'.$user->id);
        }

    }

    public function render()
    {
        return view('livewire.welcome');
    }
}
