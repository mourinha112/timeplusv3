<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Corrige a constraint única da tabela appointments.
     * 
     * ANTES: unique(appointment_date, appointment_time)
     *   - Impedia QUALQUER agendamento no mesmo horário, mesmo de especialistas diferentes
     *   - Bug: especialista A não podia ter consulta às 16h se especialista B já tinha
     *
     * DEPOIS: unique(specialist_id, appointment_date, appointment_time)
     *   - Permite que diferentes especialistas tenham consultas no mesmo horário
     *   - Impede apenas duplicatas do MESMO especialista no mesmo horário
     */
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            // Remover a constraint antiga (incorreta)
            $table->dropUnique(['appointment_date', 'appointment_time']);
            
            // Adicionar a constraint correta (incluindo specialist_id)
            $table->unique(['specialist_id', 'appointment_date', 'appointment_time'], 'appointments_specialist_date_time_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            // Remover a constraint nova
            $table->dropUnique('appointments_specialist_date_time_unique');
            
            // Restaurar a constraint antiga
            $table->unique(['appointment_date', 'appointment_time']);
        });
    }
};
