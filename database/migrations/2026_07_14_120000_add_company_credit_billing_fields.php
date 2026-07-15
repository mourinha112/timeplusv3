<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->string('gateway_customer_id')->nullable()->after('email');
        });

        Schema::table('company_plans', function (Blueprint $table) {
            /* Limite universal de créditos que cada funcionário pode consumir por mês (null = sem limite) */
            $table->unsignedInteger('credits_per_employee')->nullable()->after('monthly_credits');

            /* Data de contratação — define o 1º mês (R$30/funcionário para o corretor) */
            $table->date('contracted_at')->nullable()->after('next_billing_date');
        });
    }

    public function down(): void
    {
        Schema::table('company_plans', function (Blueprint $table) {
            $table->dropColumn(['credits_per_employee', 'contracted_at']);
        });

        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('gateway_customer_id');
        });
    }
};
