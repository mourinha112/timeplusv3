<?php

namespace Tests\Feature\Livewire\User\Specialist;

use App\Livewire\User\Specialist\Card;
use Livewire\Livewire;
use Tests\TestCase;

class CardTest extends TestCase
{
    public function test_renders_successfully()
    {
        Livewire::test(Card::class)
            ->assertStatus(200);
    }
}
