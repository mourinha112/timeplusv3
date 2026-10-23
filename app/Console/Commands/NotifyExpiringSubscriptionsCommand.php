<?php

namespace App\Console\Commands;

use App\Models\Subscribe;
use App\Notifications\User\SubscriptionExpiringNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

/**
 * Avisa os usuários cuja assinatura está perto de vencer. Roda uma vez por dia;
 * cada assinatura recebe no máximo um aviso por marco (7, 3 e 0 dias).
 */
class NotifyExpiringSubscriptionsCommand extends Command
{
    /** Dias que disparam aviso antes do vencimento. */
    private const MILESTONES = [7, 3, 0];

    protected $signature = 'subscriptions:notify-expiring';

    protected $description = 'Envia aviso por e-mail das assinaturas que vencem em 7, 3 e 0 dias';

    public function handle(): int
    {
        $sent = 0;

        foreach (self::MILESTONES as $daysLeft) {
            $targetDate = now()->addDays($daysLeft)->toDateString();

            $subscribes = Subscribe::query()
                ->where('billing_status', Subscribe::STATUS_ACTIVE)
                ->whereDate('end_date', $targetDate)
                ->with(['user', 'plan'])
                ->get();

            foreach ($subscribes as $subscribe) {
                if (!$subscribe->user || !$subscribe->plan) {
                    continue;
                }

                try {
                    $subscribe->user->notify(new SubscriptionExpiringNotification($subscribe, $daysLeft));
                    $sent++;
                } catch (\Exception $e) {
                    Log::error('Falha ao avisar usuário sobre assinatura a vencer', [
                        'subscribe_id' => $subscribe->id,
                        'days_left'    => $daysLeft,
                        'message'      => $e->getMessage(),
                    ]);
                }
            }
        }

        $this->info("Avisos de vencimento enviados: {$sent}.");

        return self::SUCCESS;
    }
}
