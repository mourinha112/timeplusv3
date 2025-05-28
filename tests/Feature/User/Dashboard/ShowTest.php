<?php

use App\Livewire\User\Dashboard\Show;
use App\Models\User;
use Livewire\Livewire;

it('renders successfully', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    Livewire::test(Show::class)
        ->assertOk()
        ->assertSee($user->name);
});
