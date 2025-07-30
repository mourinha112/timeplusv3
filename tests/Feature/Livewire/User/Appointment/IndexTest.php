<?php

namespace Tests\Feature\Livewire\User\Appointment;

use App\Livewire\User\Appointment\Index;
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
