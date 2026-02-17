<?php

namespace App\Services;

use Firebase\JWT\JWT;

class JitsiService
{
    private function generateRoomCode(): string
    {
        $alphabet = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        $p1       = $p2 = '';

        for ($i = 0; $i < 4; $i++) {
            $p1 .= $alphabet[random_int(0, strlen($alphabet) - 1)];
        }

        for ($i = 0; $i < 4; $i++) {
            $p2 .= $alphabet[random_int(0, strlen($alphabet) - 1)];
        }

        return $p1 . '-' . $p2;
    }

    private function sanitizeDisplayName(string $displayName): string
    {
        // Remover caracteres que podem causar problemas no parsing
        $sanitized = trim($displayName);

        // Se estiver vazio, usar nome padrão
        if (empty($sanitized)) {
            return 'Convidado';
        }

        // Limitar o tamanho e remover caracteres problemáticos
        $sanitized = mb_substr($sanitized, 0, 60);

        return $sanitized;
    }

    public function createRoomCode(): string
    {
        return $this->generateRoomCode();
    }

    public function buildJwt(string $roomCode, string $displayName): string
    {
        $appId  = config('jitsi.app_id');
        $secret = config('jitsi.secret');
        $ttl    = (int) config('jitsi.ttl', 3600);
        $now    = time();

        // Sanitizar o displayName para evitar problemas de parsing
        $sanitizedName = $this->sanitizeDisplayName($displayName);

        $payload = [
            'iss'     => $appId,
            'aud'     => $appId,
            'sub'     => config('jitsi.domain'),
            'exp'     => $now + $ttl,
            'room'    => $roomCode,
            // 'nbf'     => $now - 10,
            'context' => [
                'user' => ['name' => $sanitizedName],
            ],
        ];

        return JWT::encode($payload, $secret, 'HS256');
    }

    public function buildEmbedUrl(string $roomCode, string $displayName): string
    {
        $jitsiDomain = config('jitsi.domain');

        // Produção: usar Jitsi privado com JWT autenticado (sem limite de tempo)
        if (app()->environment('production')) {
            $jwt = $this->buildJwt($roomCode, $displayName);

            return "https://{$jitsiDomain}/" . urlencode($roomCode) . '?jwt=' . urlencode($jwt);
        }

        // Local/dev: usar meet.jit.si gratuito para testes
        $sanitizedName = $this->sanitizeDisplayName($displayName);

        return "https://meet.jit.si/" . urlencode($roomCode) . "#userInfo.displayName=" . urlencode($sanitizedName);
    }
}
