<?php

namespace Tests\Feature\Livewire\Master\Auth;

use App\Livewire\Master\Auth\Login;
use Livewire\Livewire;
use Tests\TestCase;

class LoginTest extends TestCase
{
    public function test_renders_successfully()
    {
        Livewire::test(Login::class)
            ->assertStatus(200);
    }
}
