<?php

namespace Tests\Feature\Livewire\Master\Dashboard;

use App\Livewire\Master\Dashboard\Show;
use Livewire\Livewire;
use Tests\TestCase;

class ShowTest extends TestCase
{
    public function test_renders_successfully()
    {
        Livewire::test(Show::class)
            ->assertStatus(200);
    }
}
