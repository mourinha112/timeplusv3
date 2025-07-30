<?php

namespace Tests\Feature\Livewire\Specialist\Client;

use App\Livewire\Specialist\Client\Index;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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
