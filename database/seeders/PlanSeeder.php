<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('plans')->insert([
            ['name' => 'Mensal', 'price' => 49.99, 'discount_percentage' => 5, 'duration_days' => 30],
            ['name' => 'Semestral', 'price' => 89.99, 'discount_percentage' => 10, 'duration_days' => 180],
            ['name' => 'Anual', 'price' => 299.99, 'discount_percentage' => 20, 'duration_days' => 365],
        ]);
    }
}
