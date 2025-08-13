<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            GenderSeeder::class,
            SpecialtySeeder::class,
            ReasonSeeder::class,
            TrainingTypeSeeder::class,
            StateSeeder::class,
            PlanSeeder::class,
            UserSeeder::class,
            SpecialistSeeder::class,
        ]);
    }
}
