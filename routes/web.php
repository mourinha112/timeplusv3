<?php

use App\Livewire\{Specialist, User, Welcome};
use Illuminate\Support\Facades\{Auth, Route};

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
 * Logout Routes
 */
Route::group(['middleware' => 'auth:user'], function () {
    Route::get('logout', function () {
        auth()->guard('user')->logout();

        return redirect()->route('user.auth.login');
    })->name('user.auth.logout');
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
 * Logout Routes
 */
Route::group(['middleware' => 'auth:specialist'], function () {
    Route::get('specialist/logout', function () {
        Auth::guard('specialist')->logout();

        return redirect()->route('specialist.auth.login');
    })->name('specialist.auth.logout');
});

/**
 * Application Routes
 */
Route::group(['middleware' => ['auth:specialist', 'onboarding:specialist']], function () {
    /* Onboarding */
    Route::get('specialist/onboarding/personal-details', Specialist\Onboarding\PersonalDetail::class)->name('specialist.onboarding.personal-details');
    Route::get('specialist/onboarding/professional-details', Specialist\Onboarding\ProfessionalDetail::class)->name('specialist.onboarding.professional-details');

    /* Appointments */
    Route::get('specialist/appointments', Specialist\Appointment\Index::class)->name('specialist.appointment.index');

    /* Availabilities */
    Route::get('specialist/availabilities', Specialist\Availability\Index::class)->name('specialist.availability.index');

    /* Clients */
    Route::get('specialist/clients', Specialist\Client\Index::class)->name('specialist.client.index');

    /* Profile */
    Route::get('specialist/profile/personal-details', Specialist\Profile\PersonalDetail::class)->name('specialist.profile.personal-details');
    Route::get('specialist/profile/professional-details', Specialist\Profile\ProfessionalDetail::class)->name('specialist.profile.professional-details');
});
