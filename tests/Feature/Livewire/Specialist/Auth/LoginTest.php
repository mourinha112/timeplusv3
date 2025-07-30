<?php

namespace Tests\Feature\Livewire\Specialist\Auth;

use App\Livewire\Specialist\Auth\Login;
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
