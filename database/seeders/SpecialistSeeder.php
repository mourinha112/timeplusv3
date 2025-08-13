<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SpecialistSeeder extends Seeder
{
    public function run(): void
    {
        \App\Models\Specialist::factory()->create([
            'email'        => 'thiagogarciafraga99@gmail.com',
            'birth_date'   => '25/09/1999',
            'password'     => bcrypt('12345678'),
            'name'         => 'Thiago Garcia Fraga',
            'crp'          => '123456',
            'cpf'          => '09585069903',
            'specialty_id' => 1,
            'gender_id'    => 1,
            'year_started_acting' => 2015
        ]);
    }
}
