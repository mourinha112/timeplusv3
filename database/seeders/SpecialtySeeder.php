<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SpecialtySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('specialties')->insert([
            ['name' => 'Psicólogo(a)'],
            ['name' => 'Psicanalista'],
            ['name' => 'Terapeuta'],
            ['name' => 'Coach'],
        ]);
    }
}
