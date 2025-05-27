<?php

use App\Livewire\User\Auth\Login;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(Login::class)
        ->assertOk();
});

it('should be able to user login', function () {
    $user = User::factory()->create([
        'email'    => 'joe@doe.com',
        'password' => 'password',
    ]);

    Livewire::test(Login::class)
        ->set('email', 'joe@doe.com')
        ->set('password', 'password')
        ->call('submit')
        ->assertHasNoErrors()
        ->assertRedirectToRoute('user.dashboard.show');

    expect(Auth::check())->toBeTrue()
        ->and(Auth::user())->id->toBe($user->id);
});

it('should make sure to inform the user an error when email and password doesnt work', function () {
    Livewire::test(Login::class)
        ->set('email', 'joe@doe.com')
        ->set('password', 'password')
        ->call('submit')
        ->assertHasErrors(['invalidCredentials'])
        ->assertSee(trans('auth.failed'));
});

it('should make sure that the rate limiting is blocking after 5 attempts', function () {
    $user = User::factory()->create();

    for ($i = 0; $i < 5; $i++) {
        Livewire::test(Login::class)
            ->set('email', $user->email)
            ->set('password', 'wrong-password')
            ->call('submit')
            ->assertHasErrors(['invalidCredentials']);
    }

    Livewire::test(Login::class)
        ->set('email', $user->email)
        ->set('password', 'wrong-password')
        ->call('submit')
        ->assertHasErrors(['rateLimiter']);
});

it('fields must validated', function ($f) {
    Livewire::test(Login::class)
        ->set($f->field, $f->value)
        ->call('submit')
        ->assertHasErrors([$f->field => $f->rule]);
})->with([
    'email::required' => (object)['field' => 'email', 'value' => '', 'rule' => 'required'],
    'email::max:255'  => (object)['field' => 'email', 'value' => str_repeat('*', 256), 'rule' => 'max'],
    'email::email'    => (object)['field' => 'email', 'value' => 'not-an-email', 'rule' => 'email'],

    'password::required' => (object)['field' => 'password', 'value' => '', 'rule' => 'required'],
    'password::max:255'  => (object)['field' => 'password', 'value' => str_repeat('*', 256), 'rule' => 'max'],
]);
