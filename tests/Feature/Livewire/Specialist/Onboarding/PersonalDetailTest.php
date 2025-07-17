<?php

namespace Tests\Feature\Livewire\Specialist\Onboarding;

use App\Livewire\Specialist\Onboarding\PersonalDetail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class PersonalDetailTest extends TestCase
{
    public function test_renders_successfully()
    {
        Livewire::test(PersonalDetail::class)
            ->assertStatus(200);
    }
}
