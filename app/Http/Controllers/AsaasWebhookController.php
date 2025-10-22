<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Payment;
use App\Models\Room;
use App\Models\Subscribe;
use App\Notifications\User\PaymentApprovedNotification;
use App\Notifications\User\SubscriptionActiveNotification;
use App\Services\JitsiService;
use Illuminate\Http\{JsonResponse, Request};
use Illuminate\Support\Facades\Log;

class AsaasWebhookController extends Controller
{
    /**
     * Processa webhooks do Asaas.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function handle(Request $request): JsonResponse
    {
        try {
            // Validar token do webhook
            if (!$this->validateWebhookToken($request)) {
                Log::warning('Webhook Asaas: Token inválido', [
                    'ip'      => $request->ip(),
                    'headers' => $request->headers->all(),
                ]);

                return response()->json(['error' => 'Unauthorized'], 401);
            }

            // Obter dados do webhook
            $event   = $request->input('event');
            $payment = $request->input('payment');

            // Log do evento recebido
            Log::info('Webhook Asaas recebido', [
                'event'      => $event,
                'payment_id' => $payment['id'] ?? null,
                'status'     => $payment['status'] ?? null,
            ]);

            // Processar evento baseado no tipo
            switch ($event) {
                case 'PAYMENT_CREATED':
                    // Apenas loga, não precisa atualizar nada
                    Log::info('Webhook Asaas: Pagamento criado', [
                        'payment_id' => $payment['id'] ?? null,
                    ]);

                    break;

                case 'PAYMENT_RECEIVED':
                case 'PAYMENT_CONFIRMED':
                    $this->handlePaymentConfirmed($payment);

                    break;

                case 'PAYMENT_OVERDUE':
                    $this->handlePaymentOverdue($payment);

                    break;

                case 'PAYMENT_DELETED':
                case 'PAYMENT_REFUNDED':
                    $this->handlePaymentCancelled($payment);

                    break;

                case 'PAYMENT_UPDATED':
                    $this->handlePaymentUpdated($payment);

                    break;

                default:
                    Log::info('Webhook Asaas: Evento não tratado', ['event' => $event]);
            }

            return response()->json(['success' => true], 200);

        } catch (\Exception $e) {
            Log::error('Erro ao processar webhook Asaas', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
                'payload' => $request->all(),
            ]);

            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    /**
     * Valida o token do webhook do Asaas.
     *
     * @param Request $request
     * @return bool
     */
    protected function validateWebhookToken(Request $request): bool
    {
        $webhookToken = config('services.asaas.webhook_token');

        if (empty($webhookToken)) {
            Log::warning('Webhook Asaas: Token não configurado no .env');

            return true; // Permite em desenvolvimento se não tiver token configurado
        }

        // O Asaas envia o token no header 'asaas-access-token'
        $receivedToken = $request->header('asaas-access-token');

        return hash_equals($webhookToken, $receivedToken ?? '');
    }

    /**
     * Processa pagamento confirmado/recebido.
     *
     * @param array $paymentData
     * @return void
     */
    protected function handlePaymentConfirmed(array $paymentData): void
    {
        $gatewayOrderId = $paymentData['id'];

        // Buscar pagamento no banco
        $payment = Payment::where('gateway_order_id', $gatewayOrderId)->first();

        if (!$payment) {
            Log::warning('Webhook Asaas: Pagamento não encontrado', [
                'gateway_order_id' => $gatewayOrderId,
            ]);

            return;
        }

        // Atualizar status do pagamento
        $payment->update([
            'status'  => 'paid',
            'paid_at' => now(),
        ]);

        Log::info('Webhook Asaas: Pagamento confirmado', [
            'payment_id'       => $payment->id,
            'gateway_order_id' => $gatewayOrderId,
            'amount'           => $payment->amount,
            'payment_method'   => $payment->payment_method,
        ]);

        // Se for um appointment, criar sala automaticamente e enviar notificação
        if ($payment->payable_type === 'App\Models\Appointment' && $payment->payable instanceof Appointment) {
            $appointment = $payment->payable;
            $this->createRoomForAppointment($appointment);

            // Enviar notificação de pagamento aprovado ao usuário
            try {
                $appointment->user->notify(new PaymentApprovedNotification($appointment, $payment));
            } catch (\Exception $e) {
                Log::error('Webhook Asaas: Erro ao enviar notificação de pagamento aprovado', [
                    'error' => $e->getMessage(),
                ]);
            }
        }

        // Se for uma assinatura, enviar notificação
        if ($payment->payable_type === 'App\Models\Subscribe' && $payment->payable instanceof Subscribe) {
            try {
                $subscribe = $payment->payable;
                $subscribe->user->notify(new SubscriptionActiveNotification($subscribe, $payment));
            } catch (\Exception $e) {
                Log::error('Webhook Asaas: Erro ao enviar notificação de assinatura ativa', [
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }

    /**
     * Processa pagamento vencido.
     *
     * @param array $paymentData
     * @return void
     */
    protected function handlePaymentOverdue(array $paymentData): void
    {
        $gatewayOrderId = $paymentData['id'];

        $payment = Payment::where('gateway_order_id', $gatewayOrderId)->first();

        if (!$payment) {
            return;
        }

        $payment->update([
            'status' => 'failed',
        ]);

        Log::info('Webhook Asaas: Pagamento vencido', [
            'payment_id'       => $payment->id,
            'gateway_order_id' => $gatewayOrderId,
        ]);
    }

    /**
     * Processa pagamento cancelado/estornado.
     *
     * @param array $paymentData
     * @return void
     */
    protected function handlePaymentCancelled(array $paymentData): void
    {
        $gatewayOrderId = $paymentData['id'];

        $payment = Payment::where('gateway_order_id', $gatewayOrderId)->first();

        if (!$payment) {
            return;
        }

        $payment->update([
            'status'      => 'refunded',
            'refunded_at' => now(),
        ]);

        Log::info('Webhook Asaas: Pagamento cancelado/estornado', [
            'payment_id'       => $payment->id,
            'gateway_order_id' => $gatewayOrderId,
        ]);
    }

    /**
     * Processa atualização de pagamento.
     *
     * @param array $paymentData
     * @return void
     */
    protected function handlePaymentUpdated(array $paymentData): void
    {
        $gatewayOrderId = $paymentData['id'];
        $status         = $paymentData['status'] ?? null;

        $payment = Payment::where('gateway_order_id', $gatewayOrderId)->first();

        if (!$payment) {
            return;
        }

        // Mapear status do Asaas para o sistema
        $mappedStatus = $this->mapAsaasStatusToSystem($status);

        $payment->update([
            'status' => $mappedStatus,
        ]);

        Log::info('Webhook Asaas: Pagamento atualizado', [
            'payment_id'       => $payment->id,
            'gateway_order_id' => $gatewayOrderId,
            'old_status'       => $payment->getOriginal('status'),
            'new_status'       => $mappedStatus,
        ]);
    }

    /**
     * Mapeia status do Asaas para status do sistema.
     *
     * @param string|null $asaasStatus
     * @return string
     */
    protected function mapAsaasStatusToSystem(?string $asaasStatus): string
    {
        $statusMap = [
            'PENDING'                      => 'pending_payment',
            'RECEIVED'                     => 'paid',
            'CONFIRMED'                    => 'paid',
            'OVERDUE'                      => 'failed',
            'REFUNDED'                     => 'refunded',
            'RECEIVED_IN_CASH'             => 'paid',
            'REFUND_REQUESTED'             => 'refunded',
            'CHARGEBACK_REQUESTED'         => 'failed',
            'CHARGEBACK_DISPUTE'           => 'failed',
            'AWAITING_CHARGEBACK_REVERSAL' => 'failed',
        ];

        return $statusMap[$asaasStatus] ?? 'pending';
    }

    /**
     * Cria sala de videochamada para o appointment.
     *
     * @param Appointment $appointment
     * @return void
     */
    protected function createRoomForAppointment(Appointment $appointment): void
    {
        try {
            // Verificar se já existe sala para este appointment
            $existingRoom = Room::where('appointment_id', $appointment->id)->first();

            if ($existingRoom) {
                Log::info('Webhook Asaas: Sala já existe para este appointment', [
                    'appointment_id' => $appointment->id,
                    'room_code'      => $existingRoom->code,
                ]);

                return;
            }

            // Criar nova sala
            $jitsiService = new JitsiService();
            $roomCode     = $jitsiService->createRoomCode();

            $room = Room::create([
                'code'           => $roomCode,
                'status'         => 'closed',
                'created_by'     => $appointment->user_id,
                'appointment_id' => $appointment->id,
            ]);

            Log::info('Webhook Asaas: Sala criada automaticamente após confirmação de pagamento', [
                'appointment_id'       => $appointment->id,
                'room_code'            => $roomCode,
                'appointment_datetime' => $appointment->appointment_date . ' ' . $appointment->appointment_time,
                'user_id'              => $appointment->user_id,
                'specialist_id'        => $appointment->specialist_id,
            ]);

            // Notificar especialista (pode ser implementado depois)
            try {
                $specialist = $appointment->specialist;
                Log::info('Webhook Asaas: Notificação ao especialista', [
                    'specialist_id'    => $specialist->id,
                    'specialist_email' => $specialist->email,
                    'room_code'        => $roomCode,
                ]);
            } catch (\Exception $e) {
                Log::error('Webhook Asaas: Erro ao notificar especialista', [
                    'error' => $e->getMessage(),
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Webhook Asaas: Erro ao criar sala automaticamente', [
                'appointment_id' => $appointment->id,
                'error'          => $e->getMessage(),
                'trace'          => $e->getTraceAsString(),
            ]);
        }
    }
}
