<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            $table->string('gateway_payment_id')->unique();

            $table->foreignId('user_id')->constrained();
            $table->foreignId('appointment_id')->constrained();

            $table->decimal('value', 10, 2);
            $table->enum('billing_type', ['credit_card', 'pix']);

            $table->enum('status', [
                'PENDING',
                'RECEIVED',
                'CONFIRMED',
                'OVERDUE',
                'REFUNDED',
                'RECEIVED_IN_CASH',
                'REFUND_REQUESTED',
                'REFUND_IN_PROGRESS',
                'CHARGEBACK_REQUESTED',
                'CHARGEBACK_DISPUTE',
                'AWAITING_CHARGEBACK_REVERSAL',
                'DUNNING_REQUESTED',
                'DUNNING_RECEIVED',
                'AWAITING_RISK_ANALYSIS',
            ])->default('PENDING');

            $table->date('due_date');
            $table->text('description');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
