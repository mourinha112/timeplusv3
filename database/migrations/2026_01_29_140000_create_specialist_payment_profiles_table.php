<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Tabela para armazenar os dados de pagamento do especialista.
     * Permite que o especialista cadastre seus dados bancários para receber repasses.
     */
    public function up(): void
    {
        Schema::create('specialist_payment_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('specialist_id')->constrained()->onDelete('cascade');
            
            // Dados do titular
            $table->string('holder_name');
            $table->string('holder_cpf', 14);
            
            // Tipo de conta para recebimento
            $table->enum('payment_type', ['pix', 'bank_account'])->default('pix');
            
            // Dados PIX (se payment_type = pix)
            $table->enum('pix_key_type', ['cpf', 'email', 'phone', 'random'])->nullable();
            $table->string('pix_key')->nullable();
            
            // Dados bancários (se payment_type = bank_account)
            $table->string('bank_code', 10)->nullable();
            $table->string('bank_name')->nullable();
            $table->string('agency', 10)->nullable();
            $table->string('account_number', 20)->nullable();
            $table->string('account_digit', 2)->nullable();
            $table->enum('account_type', ['checking', 'savings'])->nullable();
            
            // ID da conta/subconta no gateway (Asaas)
            $table->string('gateway_account_id')->nullable();
            $table->string('gateway_wallet_id')->nullable();
            
            // Status e verificação
            $table->boolean('is_verified')->default(false);
            $table->timestamp('verified_at')->nullable();
            
            // Configurações de repasse
            $table->decimal('platform_fee_percentage', 5, 2)->default(20.00); // Taxa padrão 20%
            
            $table->timestamps();
            
            // Índices
            $table->index('specialist_id');
            $table->index('gateway_account_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('specialist_payment_profiles');
    }
};
