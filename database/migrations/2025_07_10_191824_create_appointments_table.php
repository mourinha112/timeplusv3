<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained();
            $table->foreignId('specialist_id')->constrained();

            $table->decimal('total_value', 10, 2);
            $table->date('appointment_date');
            $table->time('appointment_time');

            $table->text('notes')->nullable();

            $table->enum('status', ['scheduled', 'cancelled', 'completed'])->default('scheduled');

            /* Índices */
            $table->index(['appointment_date', 'appointment_time']);
            $table->index('user_id');

            /* Evitar agendamentos duplicados no mesmo horário */
            $table->unique(['appointment_date', 'appointment_time']);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
