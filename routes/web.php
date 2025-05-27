<?php

use App\Livewire\{User, Welcome};
use Illuminate\Support\Facades\Route;

Route::get('/', Welcome::class)->name('welcome');

Route::get('/login', User\Auth\Login::class)->name('user.auth.login')->middleware('guest');
Route::get('/register', User\Auth\Register::class)->name('user.auth.register')->middleware('guest');

Route::get('/logout', fn () => auth()->logout())->name('user.auth.logout');
