<?php

use App\Livewire\Gallery;
use App\Livewire\Home;
use App\Livewire\Welcome;
use Illuminate\Support\Facades\Route;

Route::get('/', Welcome::class)->name('welcome');
Route::get('/gallery', Gallery::class)->name('gallery');
Route::get('/u/{ulid}', Home::class)->name('home');
