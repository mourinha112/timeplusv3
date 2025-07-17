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
Route::group(['middleware' => 'guest:user'], function () {
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
Route::group(['middleware' => 'guest:specialist'], function () {
    /* Login */
    Route::get('specialist/login', Specialist\Auth\Login::class)->name('specialist.auth.login');

    /* Register */
    Route::get('specialist/register', Specialist\Auth\Register::class)->name('specialist.auth.register');
});

/**
 * Onboarding Routes
 */
Route::group(['middleware' => ['auth:specialist', 'onboarding:specialist']], function () {
    Route::get('specialist/onboarding', Specialist\Onboarding\PersonalDetail::class)->name('specialist.onboarding.personal-details');
});

/**
 * Application Routes
 */
Route::group(['middleware' => ['auth:specialist', 'onboarding:specialist']], function () {
    Route::get('specialist/dashboard', function () {
        return 'Welcome to the Specialist Dashboard';
    })->name('specialist.dashboard.show');
});
