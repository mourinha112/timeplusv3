<?php

use App\Livewire\User\Auth\Login;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(Login::class)
        ->assertStatus(200);
});
