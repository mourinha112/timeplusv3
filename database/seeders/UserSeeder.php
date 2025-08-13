<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        \App\Models\User::factory()->create([
            'name'     => 'Thiago User',
            'email'    => 'thiagogarciafraga99@gmail.com',
            'password' => bcrypt('12345678'),
        ]);
    }
}
