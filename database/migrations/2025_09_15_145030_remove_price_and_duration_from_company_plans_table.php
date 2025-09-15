<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('company_plans', function (Blueprint $table) {
            // Primeiro remover a foreign key constraint
            $table->dropForeign(['plan_id']);

            // Depois remover as colunas
            $table->dropColumn(['price', 'duration_days', 'plan_id']);
        });
    }

    public function down(): void
    {
        Schema::table('company_plans', function (Blueprint $table) {
            $table->foreignId('plan_id')->nullable()->constrained()->onDelete('set null');
            $table->integer('duration_days')->nullable();
            $table->decimal('price', 10, 2)->nullable();
        });
    }
};
