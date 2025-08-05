<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TrainingTypeSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('training_types')->insert([
            ['name' => 'Graduação'],
            ['name' => 'Mestrado'],
            ['name' => 'Doutorado'],
            ['name' => 'Pós-Graduação/MBA'],
            ['name' => 'Especialização'],
            ['name' => 'Outros cursos'],
        ]);
    }
}
