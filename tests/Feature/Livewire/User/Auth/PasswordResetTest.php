<?php

namespace Tests\Feature\Livewire\User\Auth;

use App\Livewire\User\Auth\PasswordReset;
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
