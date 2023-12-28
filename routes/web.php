<?php

use App\Livewire\Home;
use App\Livewire\Welcome;
use Illuminate\Support\Facades\Route;

Route::get('/', Welcome::class)->name('welcome');
Route::get('/{ulid}', Home::class)->name('home');
