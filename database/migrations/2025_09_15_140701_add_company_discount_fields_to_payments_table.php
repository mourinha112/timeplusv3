<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->foreignId('company_id')->nullable()->constrained()->onDelete('set null');
            $table->decimal('original_amount', 10, 2)->nullable();
            $table->decimal('company_discount_amount', 10, 2)->nullable()->default(0);
            $table->decimal('discount_percentage', 5, 2)->nullable()->default(0);
            $table->string('company_plan_name')->nullable();

            // Índices para performance
            $table->index('company_id');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
            $table->dropColumn([
                'company_id',
                'original_amount',
                'company_discount_amount',
                'discount_percentage',
                'company_plan_name',
            ]);
        });
    }
};
