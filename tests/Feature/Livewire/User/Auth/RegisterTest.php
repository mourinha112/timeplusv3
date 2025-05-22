<?php

use App\Livewire\User\Auth\Register;
use App\Models\User;
use Livewire\Livewire;

use function Pest\Laravel\{assertDatabaseCount, assertDatabaseHas};

it('should render the component', function () {
    Livewire::test(Register::class)
        ->assertOk();
});

it('should be able to register a user', function () {
    Livewire::test(Register::class)
        ->set('name', 'John Doe')
        ->set('email', 'joe@doe.com')
        ->set('password', 'password')
        ->set('password_confirmation', 'password')
        ->call('submit')
        ->assertHasNoErrors()
        ->assertRedirectToRoute('welcome');

    assertDatabaseHas('users', [
        'name'  => 'John Doe',
        'email' => 'joe@doe.com',
    ]);

    assertDatabaseCount('users', 1);

    expect(auth()->check())->toBeTrue();
    expect(auth()->user()->id)->toBe(User::first()->id);

    // expect(auth()->check())
    //     ->and(auth()->user())
    //     ->id->toBe(User::first()->id);
});

it('fields must validated', function ($f) {
    if (property_exists($f, 'before')) {
        ($f->before)();
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
    'email::unique'   => (object)['field' => 'email', 'value' => 'existing@example.com', 'rule' => 'unique', 'before' => fn () => User::factory()->create(['email' => 'existing@example.com'])],

    'password::required'  => (object)['field' => 'password', 'value' => '', 'rule' => 'required'],
    'password::max:255'   => (object)['field' => 'password', 'value' => str_repeat('*', 256), 'rule' => 'max'],
    'password::min:255'   => (object)['field' => 'password', 'value' => str_repeat('*', 7), 'rule' => 'min'],
    'password::confirmed' => (object)['field' => 'password', 'value' => 'secret123', 'confirmation' => 'different123', 'rule' => 'confirmed'],
]);
