<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompanySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('companies')->insert([
            [
                'name'       => '[Company] TimePlus',
                'cnpj'       => '12345678000166',
                'email'      => 'company@timeplus.com.br',
                'password'   => bcrypt('q1w2e3r4'),
                'phone'      => '11999887766',
                'address'    => 'Rua São Paulo, 1234',
                'city'       => 'São Paulo',
                'state'      => 'SP',
                'zip_code'   => '01234567',
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
