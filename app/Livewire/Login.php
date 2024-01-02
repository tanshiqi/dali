<?php

namespace App\Livewire;

use App\Models\Client;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Component;

class Login extends Component
{
    public $client_id = null;

    public $user_id = null;

    public $showErrorIndicator = false;

    public $lockPin = false;

    public function pinCompleted($pindata)
    {
        sleep(1);

        // 查找匹配的客户
        $client = Client::where('pin', $pindata)
            ->where(function ($query) {
                $query->whereNull('expired_at')
                    ->orWhere('expired_at', '>', now());
            })->first();

        if ($client) {
            $this->client_id = $client->id;

            //如果用户存在则直接登录
            if ($this->checkUserExist()) {
                Auth::loginUsingId($this->user_id);
            } else {
                $user = User::create([
                    'name' => Str::random(12),
                    'client_id' => $client->id,
                ]);
                $this->user_id = $user->id;
                Auth::login($user);
            }

            return redirect('/u/'.$this->user_id);

        } else {
            // 如果没有找到匹配的客户，返回错误信息
            $this->showErrorIndicator = true;
            $this->js('
                const inputs = document.querySelectorAll("#pin input");
                setTimeout(() => {
                    inputs.forEach(input => input.value = "");
                    $wire.lockPin = false;
                }, 1500);
                setTimeout(() => {
                    inputs[0].focus();
                }, 1510);
            ');
        }
    }

    public function checkUserExist()
    {
        $intendedPath = parse_url(session('url.intended'), PHP_URL_PATH);
        if ($intendedPath && str_starts_with($intendedPath, '/u/')) {
            $userId = request()->create($intendedPath)->segment(2);
            if (User::where('id', $userId)->where('client_id', $this->client_id)->exists()) {
                $this->user_id = $userId;

                return true;
            }
        }

        return false;

    }

    public function render()
    {
        return view('livewire.login');
    }
}
