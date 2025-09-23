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
    Route::get('entrar', User\Auth\Login::class)->name('user.auth.login');

    /* Register */
    Route::get('registrar', User\Auth\Register::class)->name('user.auth.register');

    /* Password Recovery */
    Route::get('recuperar-senha', User\Auth\PasswordRecovery::class)->name('user.auth.password-recovery');

    /* Password Reset */
    Route::get('redefinir-senha/{token}', User\Auth\PasswordReset::class)->name('user.auth.password.reset');
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
    Route::get('specialists/{specialist}', User\Specialist\Show::class)->name('user.specialist.show');

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
    Route::get('especialista/entrar', Specialist\Auth\Login::class)->name('specialist.auth.login');

    /* Register */
    Route::get('especialista/registrar', Specialist\Auth\Register::class)->name('specialist.auth.register');

    /* Password Recovery */
    Route::get('especialista/recuperar-senha', Specialist\Auth\PasswordRecovery::class)->name('specialist.auth.password-recovery');

    /* Password Reset */
    Route::get('especialista/redefinir-senha/{token}', Specialist\Auth\PasswordReset::class)->name('specialist.auth.password.reset');
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
    Route::get('especialista/primeiros-passos/dados-pessoais', Specialist\Onboarding\PersonalDetail::class)->name('specialist.onboarding.personal-details');
    Route::get('especialista/primeiros-passos/dados-profissionais', Specialist\Onboarding\ProfessionalDetail::class)->name('specialist.onboarding.professional-details');

    /* Appointments */
    Route::get('especialista/agendamentos', Specialist\Appointment\Index::class)->name('specialist.appointment.index');

    /* Availabilities */
    Route::get('especialista/disponibilidades', Specialist\Availability\Index::class)->name('specialist.availability.index');

    /* Clients */
    Route::get('especialista/clientes', Specialist\Client\Index::class)->name('specialist.client.index');

    /* Profile */
    Route::get('especialista/perfil/dados-pessoais', Specialist\Profile\PersonalDetail::class)->name('specialist.profile.personal-details');
    Route::get('especialista/perfil/dados-profissionais', Specialist\Profile\ProfessionalDetail::class)->name('specialist.profile.professional-details');
});

/* ----------------- */
/* Master Routes */
/* ----------------- */

/**
 * Authentication Routes
 */
Route::group(['middleware' => 'guest:master'], function () {
    /* Login */
    Route::get('administrador/entrar', Master\Auth\Login::class)->name('master.auth.login');
});

/**
 * Application Routes
 */
Route::group(['middleware' => ['auth:master']], function () {
    /* Dashboard */
    Route::get('administrador/painel', Master\Dashboard\Show::class)->name('master.dashboard.show');
    /* Logout */
    Route::get('master/logout', function () {
        Auth::guard('master')->logout();

        return redirect()->route('master.auth.login');
    })->name('master.auth.logout');

    /* Tables (Index components na pasta principal) */
    Route::get('administrador/usuarios', Master\User\Index::class)->name('master.user.index');
    Route::get('administrador/especialistas', Master\Specialist\Index::class)->name('master.specialist.index');
    Route::get('administrador/empresas', Master\Company\Index::class)->name('master.company.index');
    Route::get('administrador/agendamentos', Master\Appointment\Index::class)->name('master.appointment.index');
    Route::get('administrador/pagamentos', Master\Payment\Index::class)->name('master.payment.index');
    Route::get('administrador/planos', Master\Plan\Index::class)->name('master.plan.index');

    /* CRUD Companies */
    Route::get('administrador/empresas/criar', Master\Company\Create::class)->name('master.company.create');
    Route::get('administrador/empresas/{company}/editar', Master\Company\Edit::class)->name('master.company.edit');

    /* Details */
    Route::get('administrador/usuarios/{user}', Master\User\PersonalData\Show::class)->name('master.user.personal-data.show');
    Route::get('administrador/especialistas/{specialist}', Master\Specialist\PersonalData\Show::class)->name('master.specialist.personal-data.show');
    Route::get('administrador/empresas/{company}', Master\Company\Show::class)->name('master.company.show');
    Route::get('administrador/agendamentos/{appointment}', Master\Appointment\Show::class)->name('master.appointment.show');
    Route::get('administrador/pagamentos/{payment}', Master\Payment\Show::class)->name('master.payment.show');
    Route::get('administrador/planos/{plan}', Master\Plan\Show::class)->name('master.plan.show');
    Route::get('administrador/planos/{plan}/editar', Master\Plan\Edit::class)->name('master.plan.edit');
});

/* ----------------- */
/* Company Routes   */
/* ----------------- */

/**
 * Authentication Routes
 */
Route::group(['middleware' => 'guest:company'], function () {
    /* Login */
    Route::get('empresa/entrar', App\Livewire\Company\Auth\Login::class)->name('company.auth.login');
});

/**
 * Application Routes
 */
Route::group(['middleware' => ['auth:company']], function () {
    /* Dashboard */
    Route::get('empresa/painel', App\Livewire\Company\Dashboard\Show::class)->name('company.dashboard.show');

    /* Plans */
    Route::get('empresa/planos', App\Livewire\Company\Plan\Index::class)->name('company.plan.index');
    // Route::get('empresa/planos/table', App\Livewire\Company\Plan\ShowTable::class)->name('company.plan.table');
    Route::get('empresa/planos/criar', App\Livewire\Company\Plan\Create::class)->name('company.plan.create');
    Route::get('empresa/planos/{plan}/editar', App\Livewire\Company\Plan\Edit::class)->name('company.plan.edit');
    Route::get('empresa/planos/{plan}', App\Livewire\Company\Plan\Show::class)->name('company.plan.show');

    /* Employees */
    Route::get('empresa/funcionarios', App\Livewire\Company\Employee\Index::class)->name('company.employee.index');
    Route::get('empresa/funcionarios/criar', App\Livewire\Company\Employee\Create::class)->name('company.employee.create');
    Route::get('empresa/funcionarios/{employee}/editar', App\Livewire\Company\Employee\Edit::class)->name('company.employee.edit');
    Route::get('empresa/funcionarios/{employee}', App\Livewire\Company\Employee\Show::class)->name('company.employee.show');

    /* Payments */
    Route::get('empresa/pagamentos', App\Livewire\Company\Payment\Index::class)->name('company.payment.index');
    Route::get('empresa/pagamentos/{payment}', App\Livewire\Company\Payment\Show::class)->name('company.payment.show');

    /* Profile */
    Route::get('empresa/perfil', App\Livewire\Company\Profile\Show::class)->name('company.profile.show');

    /* Logout */
    Route::get('company/logout', function () {
        Auth::guard('company')->logout();

        return redirect()->route('company.auth.login');
    })->name('company.auth.logout');
});
