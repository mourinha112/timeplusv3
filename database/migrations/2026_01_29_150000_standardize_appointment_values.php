<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Padroniza o valor das sessões para R$ 30,00
     */
    public function up(): void
    {
        DB::table('specialists')->update(['appointment_value' => 30.00]);
    }

    /**
     * Reverse the migrations.
     * Não é possível reverter para valores anteriores individuais
     */
    public function down(): void
    {
        // Não reverte - valores originais foram perdidos
    }
};
