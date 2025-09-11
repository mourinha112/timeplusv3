<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('company_plans', function (Blueprint $table) {
            $table->integer('employee_count')->default(1)->after('plan_id');
        });
    }

    public function down(): void
    {
        Schema::table('company_plans', function (Blueprint $table) {
            $table->dropColumn('employee_count');
        });
    }
};
