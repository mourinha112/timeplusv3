<?php

namespace Tests\Feature\Livewire\Specialist\Onboarding;

use App\Livewire\Specialist\Onboarding\PersonalDetail;
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
