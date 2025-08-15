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

            $table->string('gateway_order_id')->nullable()->index();
            $table->string('gateway_charge_id')->nullable()->index();

            $table->morphs('payable'); // Cria payable_type e payable_id

            $table->decimal('amount', 10, 2);
            $table->enum('payment_method', ['credit_card', 'pix'])->index();

            $table->enum('status', [
                'pending',           // Criado mas ainda não processado
                'order_created',     // Order criada na PagarMe (cartão)
                'pending_payment',   // Aguardando pagamento (PIX/boleto)
                'processing',        // Processando (cartão)
                'paid',             // Pago com sucesso
                'failed',           // Falhou
                'canceled',         // Cancelado
                'refunded',         // Estornado
                'partial_refunded'  // Estorno parcial
            ])->default('pending')->index();

            $table->timestamp('paid_at')->nullable();
            $table->timestamp('expires_at')->nullable();

            $table->string('currency', 3)->default('BRL');
            $table->text('description')->nullable();
            $table->json('metadata')->nullable();

            $table->json('gateway_payload')->nullable();

            $table->decimal('refunded_amount', 10, 2)->default(0);
            $table->timestamp('refunded_at')->nullable();
            $table->text('refund_reason')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
