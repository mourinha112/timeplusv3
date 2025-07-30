<?php

use App\Livewire\{Specialist, User, Welcome};
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

    /* Plans */
    Route::get('plans', User\Plan\Index::class)->name('user.plan.index');

    /* Subscribes */
    Route::get('subscribes', User\Subscribe\Show::class)->name('user.subscribe.show');
    Route::get('subscribes/history', User\Subscribe\History::class)->name('user.subscribe.history');

    /* Profile */
    Route::get('profile', User\Profile\Update::class)->name('user.profile.update');
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
 * Application Routes
 */
Route::group(['middleware' => ['auth:specialist', 'onboarding:specialist']], function () {
    /* Onboarding */
    Route::get('specialist/onboarding', Specialist\Onboarding\PersonalDetail::class)->name('specialist.onboarding.personal-details');

    /* Appointments */
    Route::get('specialist/appointments', Specialist\Appointment\Index::class)->name('specialist.appointment.index');

    /* Availabilities */
    Route::get('specialist/availabilities', Specialist\Availability\Index::class)->name('specialist.availability.index');

    /* Clients */
    Route::get('specialist/clients', Specialist\Client\Index::class)->name('specialist.client.index');
});
