<?php

use App\Livewire\User\Auth\Register;
use App\Models\User;
use App\Notifications\User\WelcomeNotification;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;

use function Pest\Laravel\{assertDatabaseCount, assertDatabaseHas};

it('should render the component', function () {
    Livewire::test(Register::class)
        ->assertOk();
});

it('should be able to register a user', function () {
    Livewire::test(Register::class)
        ->set('name', 'John Doe')
        ->set('cpf', '123.456.789-00')
        ->set('phone_number', '(17) 12345-6789')
        ->set('email', 'joe@doe.com')
        ->set('password', 'password')
        ->call('submit')
        ->assertHasNoErrors()
        ->assertRedirectToRoute('welcome');

    assertDatabaseHas('users', [
        'name'         => 'John Doe',
        'email'        => 'joe@doe.com',
        'cpf'          => '123.456.789-00',
        'phone_number' => '(17) 12345-6789',
    ]);

    assertDatabaseCount('users', 1);

    expect(auth()->check())->toBeTrue();
    expect(auth()->user()->id)->toBe(User::first()->id);

    // expect(auth()->check())
    //     ->and(auth()->user())
    //     ->id->toBe(User::first()->id);
});

it('fields must validated', function ($f) {
    // if (property_exists($f, 'before')) {
    //     ($f->before)();
    // }

    if ($f->rule == 'unique') {
        User::factory()->create([$f->field => $f->value]);
    }

    $test = Livewire::test(Register::class)
        ->set($f->field, $f->value);

    if (property_exists($f, 'confirmation')) {
        $test->set($f->field . '_confirmation', $f->confirmation);
    }

    $test->call('submit')
        ->assertHasErrors([$f->field => $f->rule]);
})->with([
    'name::required' => (object)['field' => 'name', 'value' => '', 'rule' => 'required'],
    'name::max:255'  => (object)['field' => 'name', 'value' => str_repeat('*', 256), 'rule' => 'max'],

    'email::required' => (object)['field' => 'email', 'value' => '', 'rule' => 'required'],
    'email::max:255'  => (object)['field' => 'email', 'value' => str_repeat('*', 256), 'rule' => 'max'],
    'email::email'    => (object)['field' => 'email', 'value' => 'not-an-email', 'rule' => 'email'],
    'email::unique'   => (object)['field' => 'email', 'value' => 'existing@example.com', 'rule' => 'unique'],
    // 'email::unique'   => (object)['field' => 'email', 'value' => 'existing@example.com', 'rule' => 'unique', 'before' => fn () => User::factory()->create(['email' => 'existing@example.com'])],

    'cpf::required' => (object)['field' => 'cpf', 'value' => '', 'rule' => 'required'],
    'cpf::max:14'   => (object)['field' => 'cpf', 'value' => str_repeat('*', 15), 'rule' => 'max'],
    'cpf::unique'   => (object)['field' => 'cpf', 'value' => '123.456.789-00', 'rule' => 'unique'],
    'cpf::regex'    => (object)['field' => 'cpf', 'value' => '12345678900', 'rule' => 'regex'],

    'phone_number::required' => (object)['field' => 'phone_number', 'value' => '', 'rule' => 'required'],
    'phone_number::max:20'   => (object)['field' => 'phone_number', 'value' => str_repeat('*', 21), 'rule' => 'max'],
    'phone_number::regex'    => (object)['field' => 'phone_number', 'value' => '1234567890', 'rule' => 'regex'],

    'password::required' => (object)['field' => 'password', 'value' => '', 'rule' => 'required'],
    'password::max:255'  => (object)['field' => 'password', 'value' => str_repeat('*', 256), 'rule' => 'max'],
    'password::min:8'    => (object)['field' => 'password', 'value' => str_repeat('*', 7), 'rule' => 'min'],
]);

it('should be send a notification welcome the new user', function () {
    Notification::fake();

    Livewire::test(Register::class)
        ->set('name', 'John Doe')
        ->set('email', 'joe@doe.com')
        ->set('cpf', '123.456.789-00')
        ->set('phone_number', '(17) 12345-6789')
        ->set('password', 'password')
        ->call('submit')
        ->assertHasNoErrors();

    $user = User::whereEmail('joe@doe.com')->first();

    Notification::assertSentTo($user, WelcomeNotification::class);
});
