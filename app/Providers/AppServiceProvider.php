<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::unguard();

        // if (env('APP_ENV') === 'production') {
        //     URL::forceSchema('https');
        // }

        $token = Cache::remember('token', 10 * 24 * 60 * 60, function () {
            return $this->getAccessToken();
        });
    }

    protected function getAccessToken()
    {
        $url = 'https://aip.baidubce.com/oauth/2.0/token';
        $params = [
            'grant_type' => 'client_credentials',
            'client_id' => config('services.baidu.client_id'),
            'client_secret' => config('services.baidu.client_secret'),
        ];

        $response = Http::get($url, $params);

        if ($response->ok()) {
            return $response->json()['access_token'];
        }
    }
}
