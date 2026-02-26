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
            'iat'     => $now,
            'nbf'     => $now - 10,
            'exp'     => $now + $ttl,
            'room'    => strtolower($roomCode),
            'context' => [
                'user' => ['name' => $sanitizedName],
            ],
        ];

        return JWT::encode($payload, $secret, 'HS256');
    }

    public function buildEmbedUrl(string $roomCode, string $displayName): string
    {
        $jitsiDomain = config('jitsi.domain');
        $secret      = config('jitsi.secret');

        // Normalizar room code para lowercase (Prosody/Jitsi usa lowercase internamente)
        $roomCodeLower = strtolower($roomCode);

        // Sempre usar Jitsi privado quando domínio e secret estiverem configurados (VPS/produção)
        if ($jitsiDomain && $secret !== '' && $secret !== null) {
            $jwt = $this->buildJwt($roomCode, $displayName);

            return "https://{$jitsiDomain}/" . urlencode($roomCodeLower) . '?jwt=' . urlencode($jwt);
        }

        // Fallback: meet.jit.si só quando não houver config (ex.: dev local sem .env)
        $sanitizedName = $this->sanitizeDisplayName($displayName);

        return "https://meet.jit.si/" . urlencode($roomCodeLower) . "#userInfo.displayName=" . urlencode($sanitizedName);
    }
}
