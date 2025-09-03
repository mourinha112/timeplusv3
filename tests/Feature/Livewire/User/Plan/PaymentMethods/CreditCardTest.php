<?php

namespace Tests\Feature\Livewire\User\Plan\PaymentMethods;

use App\Livewire\User\Plan\PaymentMethods\CreditCard;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class CreditCardTest extends TestCase
{
    public function test_renders_successfully()
    {
        Livewire::test(CreditCard::class)
            ->assertStatus(200);
    }
}
