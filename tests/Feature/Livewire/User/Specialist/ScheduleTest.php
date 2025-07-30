<?php

namespace Tests\Feature\Livewire\User\Specialist;

use App\Livewire\User\Specialist\Schedule;
use Livewire\Livewire;
use Tests\TestCase;

class ScheduleTest extends TestCase
{
    public function test_renders_successfully()
    {
        Livewire::test(Schedule::class)
            ->assertStatus(200);
    }
}
