<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('company_user', function (Blueprint $table) {
            $table->foreignId('company_plan_id')->nullable()->after('user_id')->constrained()->onDelete('set null');

            // Índice para performance
            $table->index(['company_plan_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::table('company_user', function (Blueprint $table) {
            $table->dropForeign(['company_plan_id']);
            $table->dropIndex(['company_plan_id', 'is_active']);
            $table->dropColumn('company_plan_id');
        });
    }
};
