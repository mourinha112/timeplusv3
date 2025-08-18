<?php

use App\Livewire\User\Auth\Login;
use App\Models\User;
use Illuminate\Support\Facades\{Auth, Log, RateLimiter};
use Livewire\Livewire;

beforeEach(function () {
    RateLimiter::clear('rate-limiter::test@example.com|127.0.0.1');
});

it('can render login component', function () {
    Livewire::test(Login::class)
        ->assertStatus(200)
        ->assertSee('livewire.user.auth.login');
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
        ->and(Auth::user()->email)->toBe('joe@doe.com')
        ->and(Auth::user()->id)->toBe($user->id);
});

it('shows error with invalid credentials', function () {
    User::factory()->create([
        'email'    => 'joe@doe.com',
        'password' => 'password',
    ]);

    Livewire::test(Login::class)
        ->set('email', 'joe@doe.com')
        ->set('password', 'wrong-password')
        ->call('submit')
        ->assertHasErrors(['invalidCredentials'])
        ->assertSee(trans('auth.failed'))
        ->assertSet('password', null);

    expect(Auth::check())->toBeFalse();
});

it('shows error when user does not exist', function () {
    Livewire::test(Login::class)
        ->set('email', 'joe@doe.com')
        ->set('password', 'password')
        ->call('submit')
        ->assertHasNoErrors()
        ->assertSet('password', null);

    expect(Auth::check())->toBeFalse();
});

it('should make sure that the rate limiting is blocking after 5 attempts', function () {
    $user = User::factory()->create([
        'email'    => 'joe@doe.com',
        'password' => 'password',
    ]);

    for ($i = 0; $i < 5; $i++) {
        Livewire::test(Login::class)
            ->set('email', $user->email)
            ->set('password', 'wrong-password')
            ->call('submit')
            ->assertHasErrors(['invalidCredentials'])
            ->assertSee(trans('auth.failed'))
            ->assertSet('password', null);
    }

    Livewire::test(Login::class)
        ->set('email', $user->email)
        ->set('password', 'wrong-password')
        ->call('submit')
        ->assertHasErrors(['rateLimiter'])
        ->assertSee(trans('auth.throttle'))
        ->assertSet('password', null);

    expect(Auth::check())->toBeFalse();
});

it('allows login after rate limit with correct credentials', function () {
    User::factory()->create([
        'email'    => 'joe@doe.com',
        'password' => 'password',
    ]);

    RateLimiter::hit('rate-limiter::joe@doe.com|127.0.0.1', 300);
    RateLimiter::hit('rate-limiter::joe@doe.com|127.0.0.1', 300);
    RateLimiter::hit('rate-limiter::joe@doe.com|127.0.0.1', 300);
    RateLimiter::hit('rate-limiter::joe@doe.com|127.0.0.1', 300);
    RateLimiter::hit('rate-limiter::joe@doe.com|127.0.0.1', 300);

    Livewire::test(Login::class)
        ->set('email', 'joe@doe.com')
        ->set('password', 'password')
        ->call('submit')
        ->assertHasErrors(['rateLimiter'])
        ->assertSee(trans('auth.throttle'))
        ->assertSet('email', null)
        ->assertSet('password', null);

    expect(Auth::check())->toBeFalse();
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

it('logs error when exception occurs', function () {
    Log::shouldReceive('error')
        ->once()
        ->with('Erro interno::App\Livewire\User\Auth\Login', [
            'ip'      => '127.0.0.1',
            'email'   => 'joe@doe.com',
            'message' => 'Simulated error',
        ]);

    Auth::shouldReceive('attempt')
        ->once()
        ->andThrow(new Exception('Simulated error'));

    Livewire::test(Login::class)
        ->set('email', 'joe@doe.com')
        ->set('password', 'password')
        ->call('submit')
        ->assertHasNoErrors();

    expect(Auth::check())->toBeFalse();
});

it('uses correct layout and title', function () {
    $component = new Login();

    $reflection = new ReflectionClass($component);
    $attributes = $reflection->getAttributes();

    $layoutAttribute = collect($attributes)
        ->first(fn ($attr) => $attr->getName() === 'Livewire\Attributes\Layout');

    expect($layoutAttribute)->not->toBeNull();

    $arguments = $layoutAttribute->getArguments();
    expect($arguments[0])->toBe('components.layouts.guest');
    expect($arguments[1]['title'])->toBe('Entrar');
});
