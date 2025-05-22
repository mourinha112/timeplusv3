<?php

use App\Livewire\User\Auth\Register;
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
        ->assertHasNoErrors();

    assertDatabaseHas('users', [
        'name'  => 'John Doe',
        'email' => 'joe@doe.com',
    ]);

    assertDatabaseCount('users', 1);
});
