<?php

namespace Tests\Feature\Livewire\Specialist\Profile;

use App\Livewire\Specialist\Profile\ProfessionalDetail;
use Livewire\Livewire;
use Tests\TestCase;

class ProfessionalDetailTest extends TestCase
{
    public function test_renders_successfully()
    {
        Livewire::test(ProfessionalDetail::class)
            ->assertStatus(200);
    }
}
