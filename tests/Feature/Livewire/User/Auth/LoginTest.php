<?php

use App\Livewire\User\Auth\Login;
use App\Models\User;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(Login::class)
        ->assertStatus(200);
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
        ->assertRedirectToRoute('welcome');

    expect(auth()->check())->toBeTrue()
        ->and(auth()->user())->id->toBe($user->id);
});
