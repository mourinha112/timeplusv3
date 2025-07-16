<?php

use App\Livewire\{User, Welcome};
use Illuminate\Support\Facades\Route;

Route::get('/', Welcome::class)->name('welcome');

Route::group(['middleware' => 'guest'], function () {
    Route::get('login', User\Auth\Login::class)->name('user.auth.login');
    Route::get('register', User\Auth\Register::class)->name('user.auth.register');
});

Route::group(['middleware' => 'auth:user'], function () {
    Route::get('dashboard', User\Dashboard\Show::class)->name('user.dashboard.show');
    Route::get('specialists', User\Specialist\Index::class)->name('user.specialist.index');
    Route::get('appointments', User\Appointment\Index::class)->name('user.appointment.index');
});
