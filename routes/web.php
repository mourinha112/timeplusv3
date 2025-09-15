<?php

use App\Livewire\{Master, Specialist, User, Welcome};
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

    /* Password Recovery */
    Route::get('password-recovery', User\Auth\PasswordRecovery::class)->name('user.auth.password-recovery');

    /* Password Reset */
    Route::get('password-reset/{token}', User\Auth\PasswordReset::class)->name('user.auth.password.reset');
});

// Master routes are declared below in the Master section

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
    Route::get('appointments/{appointment_id}/payment', User\Appointment\Payment::class)->name('user.appointment.payment');

    /* Plans */
    Route::get('plans', User\Plan\Index::class)->name('user.plan.index');
    Route::get('plans/{plan_id}/payment', User\Plan\Payment::class)->name('user.plan.payment');

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

    /* Password Recovery */
    Route::get('specialist/password-recovery', Specialist\Auth\PasswordRecovery::class)->name('specialist.auth.password-recovery');

    /* Password Reset */
    Route::get('specialist/password-reset/{token}', Specialist\Auth\PasswordReset::class)->name('specialist.auth.password.reset');
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

/* ----------------- */
/* Master Routes */
/* ----------------- */

/**
 * Authentication Routes
 */
Route::group(['middleware' => 'guest:master'], function () {
    /* Login */
    Route::get('master/login', Master\Auth\Login::class)->name('master.auth.login');
});

/**
 * Application Routes
 */
Route::group(['middleware' => ['auth:master']], function () {
    /* Dashboard */
    Route::get('master/dashboard', Master\Dashboard\Show::class)->name('master.dashboard.show');
    /* Logout */
    Route::get('master/logout', function () {
        Auth::guard('master')->logout();

        return redirect()->route('master.auth.login');
    })->name('master.auth.logout');

    /* Tables (Index components na pasta principal) */
    Route::get('master/users', Master\User\Index::class)->name('master.user.index');
    Route::get('master/specialists', Master\Specialist\Index::class)->name('master.specialist.index');
    Route::get('master/companies', Master\Company\Index::class)->name('master.company.index');
    Route::get('master/appointments', Master\Appointment\Index::class)->name('master.appointment.index');
    Route::get('master/payments', Master\Payment\Index::class)->name('master.payment.index');

    /* CRUD Companies */
    Route::get('master/companies/create', Master\Company\Create::class)->name('master.company.create');
    Route::get('master/companies/{company}/edit', Master\Company\Edit::class)->name('master.company.edit');

    /* Details */
    Route::get('master/users/{user}', Master\User\PersonalData\Show::class)->name('master.user.personal-data.show');
    Route::get('master/specialists/{specialist}', Master\Specialist\PersonalData\Show::class)->name('master.specialist.personal-data.show');
    Route::get('master/companies/{company}', Master\Company\Show::class)->name('master.company.show');
    Route::get('master/appointments/{appointment}', Master\Appointment\Show::class)->name('master.appointment.show');
    Route::get('master/payments/{payment}', Master\Payment\Show::class)->name('master.payment.show');
});

/* ----------------- */
/* Company Routes   */
/* ----------------- */

/**
 * Authentication Routes
 */
Route::group(['middleware' => 'guest:company'], function () {
    /* Login */
    Route::get('company/login', App\Livewire\Company\Auth\Login::class)->name('company.auth.login');
});

/**
 * Application Routes
 */
Route::group(['middleware' => ['auth:company']], function () {
    /* Dashboard */
    Route::get('company/dashboard', App\Livewire\Company\Dashboard\Show::class)->name('company.dashboard.show');

    /* Plans */
    Route::get('company/plans', App\Livewire\Company\Plan\Index::class)->name('company.plan.index');
    Route::get('company/plans/table', App\Livewire\Company\Plan\ShowTable::class)->name('company.plan.table');
    Route::get('company/plans/create', App\Livewire\Company\Plan\Create::class)->name('company.plan.create');
    Route::get('company/plans/{plan}/edit', App\Livewire\Company\Plan\Edit::class)->name('company.plan.edit');
    Route::get('company/plans/{plan}', App\Livewire\Company\Plan\Show::class)->name('company.plan.show');

    /* Employees */
    Route::get('company/employees', App\Livewire\Company\Employee\Index::class)->name('company.employee.index');
    Route::get('company/employees/table', App\Livewire\Company\Employee\ShowTable::class)->name('company.employee.table');
    Route::get('company/employees/create', App\Livewire\Company\Employee\Create::class)->name('company.employee.create');
    Route::get('company/employees/{employee}/edit', App\Livewire\Company\Employee\Edit::class)->name('company.employee.edit');
    Route::get('company/employees/{employee}', App\Livewire\Company\Employee\Show::class)->name('company.employee.show');

    /* Payments */
    // Route::get('company/payments', App\Livewire\Company\Payment\Index::class)->name('company.payment.index');

    /* Profile */
    Route::get('company/profile', App\Livewire\Company\Profile\Show::class)->name('company.profile.show');

    /* Logout */
    Route::get('company/logout', function () {
        Auth::guard('company')->logout();

        return redirect()->route('company.auth.login');
    })->name('company.auth.logout');
});
