<?php

namespace Tests\Feature\Livewire\Specialist\Availability;

use App\Livewire\Specialist\Availability\Index;
use Livewire\Livewire;
use Tests\TestCase;

class IndexTest extends TestCase
{
    public function test_renders_successfully()
    {
        Livewire::test(Index::class)
            ->assertStatus(200);
    }
}
