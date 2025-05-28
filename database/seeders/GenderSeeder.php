<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GenderSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('genders')->insert([
            ['name' => 'Homem cisgênero'],
            ['name' => 'Mulher cisgênero'],
            ['name' => 'Homem transgênero'],
            ['name' => 'Mulher transgênero'],
            ['name' => 'Gênero fluido'],
            ['name' => 'Pessoa não binária'],
            ['name' => 'Outro'],
            ['name' => 'Prefiro não responder'],
        ]);
    }
}
