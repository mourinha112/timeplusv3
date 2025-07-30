<?php

namespace Tests\Feature\Livewire\Specialist\Auth;

use App\Livewire\Specialist\Auth\Register;
use Livewire\Livewire;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    public function test_renders_successfully()
    {
        Livewire::test(Register::class)
            ->assertStatus(200);
    }
}
