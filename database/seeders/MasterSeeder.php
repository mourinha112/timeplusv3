<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('masters')->insert([
            [
                'avatar'         => null,
                'name'           => '[Master] TimePlus',
                'email'          => 'master@timeplus.com.br',
                'password'       => bcrypt('q1w2e3r4'),
                'is_active'      => true,
                'remember_token' => null,
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
        ]);
    }
}
