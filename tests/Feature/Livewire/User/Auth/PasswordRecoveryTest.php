<?php

namespace Tests\Feature\Livewire\User\Auth;

use App\Livewire\User\Auth\PasswordRecovery;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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
