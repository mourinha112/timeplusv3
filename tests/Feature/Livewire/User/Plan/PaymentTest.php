<?php

namespace Tests\Feature\Livewire\User\Plan;

use App\Livewire\User\Plan\Payment;
use Livewire\Livewire;
use Tests\TestCase;

class PaymentTest extends TestCase
{
    public function test_renders_successfully()
    {
        Livewire::test(Payment::class)
            ->assertStatus(200);
    }
}
