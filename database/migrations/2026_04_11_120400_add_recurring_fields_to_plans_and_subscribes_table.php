<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->enum('billing_cycle', ['monthly', 'quarterly', 'yearly', 'one_time'])
                ->default('monthly')
                ->after('price');

            $table->string('gateway_plan_id')->nullable()->after('billing_cycle');
        });

        Schema::table('subscribes', function (Blueprint $table) {
            $table->string('gateway_subscription_id')->nullable()->after('plan_id');

            $table->enum('billing_status', ['active', 'paused', 'cancelled', 'expired'])
                ->default('active')
                ->after('gateway_subscription_id');

            $table->date('next_billing_date')->nullable()->after('end_date');

            $table->index('gateway_subscription_id');
        });
    }

    public function down(): void
    {
        Schema::table('subscribes', function (Blueprint $table) {
            $table->dropIndex(['gateway_subscription_id']);
            $table->dropColumn(['gateway_subscription_id', 'billing_status', 'next_billing_date']);
        });

        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn(['billing_cycle', 'gateway_plan_id']);
        });
    }
};
