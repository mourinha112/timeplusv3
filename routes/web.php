<?php

use App\Livewire\{User, Specialist, Welcome};
use Illuminate\Support\Facades\Route;

/**
 * Landing Page Route
 */
Route::get('/', Welcome::class)->name('welcome');

/* ----------- */
/* User Routes */
/* ----------- */

/**
 * Authentication Routes
 */
Route::group(['middleware' => 'guest'], function () {
    /* Login */
    Route::get('login', User\Auth\Login::class)->name('user.auth.login');

    /* Register */
    Route::get('register', User\Auth\Register::class)->name('user.auth.register');
});

/**
 * Application Routes
 */
Route::group(['middleware' => 'auth:user'], function () {
    /* Dashboard */
    Route::get('dashboard', User\Dashboard\Show::class)->name('user.dashboard.show');

    /* Specialists */
    Route::get('specialists', User\Specialist\Index::class)->name('user.specialist.index');

    /* Appointments */
    Route::get('appointments', User\Appointment\Index::class)->name('user.appointment.index');
});

/* ----------------- */
/* Specialist Routes */
/* ----------------- */

/**
 * Authentication Routes
 */
Route::group(['middleware' => 'guest'], function () {
    /* Login */
    Route::get('specialist/login', Specialist\Auth\Login::class)->name('specialist.auth.login');

    /* Register */
    Route::get('specialist/register', Specialist\Auth\Register::class)->name('specialist.auth.register');
});

/**
 * Onboarding Routes
 */
Route::group(['middleware' => ['auth:specialist', 'onboarding:driver']], function () {
    //
});

/**
 * Application Routes
 */
Route::group(['middleware' => ['auth:specialist', 'onboarding:driver']], function () {
    // Route::get('dashboard', Specialist\Dashboard\Show::class)->name('specialist.dashboard.show');
});
