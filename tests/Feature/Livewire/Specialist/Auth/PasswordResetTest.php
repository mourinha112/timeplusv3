<?php

namespace Tests\Feature\Livewire\Specialist\Auth;

use App\Livewire\Specialist\Auth\PasswordReset;
use Livewire\Livewire;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    public function test_renders_successfully()
    {
        Livewire::test(PasswordReset::class)
            ->assertStatus(200);
    }
}
