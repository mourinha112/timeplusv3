<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Livewire;

it('should be able to user logout', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    Livewire::test('user.auth.logout')
        ->call('logout')
        ->assertRedirect(route('user.auth.login'));

    expect(Auth::guest())->toBeTrue();
});
