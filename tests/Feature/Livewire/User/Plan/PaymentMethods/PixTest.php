<?php

namespace Tests\Feature\Livewire\User\Plan\PaymentMethods;

use App\Livewire\User\Plan\PaymentMethods\Pix;
use Livewire\Livewire;
use Tests\TestCase;

class PixTest extends TestCase
{
    public function test_renders_successfully()
    {
        Livewire::test(Pix::class)
            ->assertStatus(200);
    }
}
