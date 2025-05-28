<?php

use App\Livewire\User\Specialist\Index;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(Index::class)
        ->assertOk();
});
