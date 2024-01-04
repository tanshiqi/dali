<?php

use App\Livewire\Gallery;
use App\Livewire\Home;
use App\Livewire\Login;
use App\Livewire\Test;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return redirect('/u/'.auth()->id());
    });
    Route::get('/u/{shortid}', Home::class)->name('home');
});

Route::middleware(['guest'])->group(function () {
    Route::get('/login', Login::class)->name('login');
});

Route::get('/admin/gallery', Gallery::class)->name('gallery');

Route::get('/test', Test::class);
