<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('company_plans', function (Blueprint $table) {
            $table->enum('billing_model', ['per_employee', 'credit_pack'])
                ->default('per_employee')
                ->after('discount_percentage');

            $table->unsignedInteger('monthly_credits')->default(0)->after('billing_model');

            $table->decimal('price_per_unit', 10, 2)->default(30.00)->after('monthly_credits');

            $table->string('gateway_subscription_id')->nullable()->after('price_per_unit');

            $table->enum('billing_status', ['active', 'paused', 'cancelled', 'expired'])
                ->default('active')
                ->after('gateway_subscription_id');

            $table->date('next_billing_date')->nullable()->after('billing_status');

            $table->index('gateway_subscription_id');
        });

        Schema::create('company_credit_balances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->foreignId('company_plan_id')->nullable()->constrained('company_plans')->nullOnDelete();

            $table->enum('bucket', ['monthly', 'extra'])->default('monthly');

            $table->unsignedInteger('credits_total');
            $table->unsignedInteger('credits_remaining');

            $table->date('valid_from');
            $table->date('valid_until');

            $table->timestamps();

            $table->index(['company_id', 'bucket', 'valid_until']);
        });

        Schema::create('company_credit_usages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_credit_balance_id')->constrained('company_credit_balances')->cascadeOnDelete();
            $table->foreignId('appointment_id')->nullable()->constrained('appointments')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->unsignedInteger('credits_used')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_credit_usages');
        Schema::dropIfExists('company_credit_balances');

        Schema::table('company_plans', function (Blueprint $table) {
            $table->dropIndex(['gateway_subscription_id']);
            $table->dropColumn([
                'billing_model',
                'monthly_credits',
                'price_per_unit',
                'gateway_subscription_id',
                'billing_status',
                'next_billing_date',
            ]);
        });
    }
};
