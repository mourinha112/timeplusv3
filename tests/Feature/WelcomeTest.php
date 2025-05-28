<?php

use App\Livewire\Welcome;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(Welcome::class)
        ->assertOk();
});
