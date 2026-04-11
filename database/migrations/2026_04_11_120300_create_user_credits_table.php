<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('user_credits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            $table->decimal('amount', 10, 2);
            $table->decimal('amount_remaining', 10, 2);

            $table->enum('source', ['cancellation', 'manual', 'refund', 'promotion'])
                ->default('cancellation');

            $table->nullableMorphs('source_reference');

            $table->timestamp('expires_at')->nullable();
            $table->timestamp('consumed_at')->nullable();

            $table->text('description')->nullable();

            $table->timestamps();

            $table->index(['user_id', 'amount_remaining', 'expires_at']);
        });

        Schema::create('user_credit_usages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_credit_id')->constrained('user_credits')->cascadeOnDelete();
            $table->foreignId('appointment_id')->nullable()->constrained('appointments')->nullOnDelete();
            $table->decimal('amount_used', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_credit_usages');
        Schema::dropIfExists('user_credits');
    }
};
