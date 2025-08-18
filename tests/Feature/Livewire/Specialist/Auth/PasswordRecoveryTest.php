<?php

namespace Tests\Feature\Livewire\Specialist\Auth;

use App\Livewire\Specialist\Auth\PasswordRecovery;
use Livewire\Livewire;
use Tests\TestCase;

class PasswordRecoveryTest extends TestCase
{
    public function test_renders_successfully()
    {
        Livewire::test(PasswordRecovery::class)
            ->assertStatus(200);
    }
}
