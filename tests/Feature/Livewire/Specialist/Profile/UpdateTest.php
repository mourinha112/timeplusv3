<?php

namespace Tests\Feature\Livewire\Specialist\Profile;

use App\Livewire\Specialist\Profile\Update;
use Livewire\Livewire;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    public function test_renders_successfully()
    {
        Livewire::test(Update::class)
            ->assertStatus(200);
    }
}
