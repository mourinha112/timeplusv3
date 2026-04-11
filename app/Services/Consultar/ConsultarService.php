<?php

namespace App\Services\Consultar;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\{Http, Log};

class ConsultarService
{
    private string $baseUrl;
    private ?string $token;
    private int $timeout;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('services.consultar.base_url'), '/');
        $this->token   = config('services.consultar.token');
        $this->timeout = (int) config('services.consultar.timeout', 15);
    }

    /**
     * Consulta um CRP pelo número de registro e UF.
     *
     * Retorna:
     *  - ['found' => true,  'active' => bool, 'data' => array]  quando localizado
     *  - ['found' => false, 'active' => false, 'data' => null]  quando não encontrado
     *
     * Lança ConsultarServiceException em erros de rede/configuração.
     */
    public function consultarCrp(string $uf, string $numeroRegistro): array
    {
        if (empty($this->token)) {
            throw new ConsultarServiceException('Token da API Consultar.IO não configurado.');
        }

        $numero = preg_replace('/\D/', '', $numeroRegistro);
        $uf     = strtolower(trim($uf));

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Token ' . $this->token,
                'Accept'        => 'application/json',
            ])
                ->timeout($this->timeout)
                ->get($this->baseUrl . '/crp/consultar', [
                    'uf'              => $uf,
                    'numero_registro' => $numero,
                ]);

            if ($response->status() === 404) {
                return ['found' => false, 'active' => false, 'data' => null];
            }

            $response->throw();

            $data = $response->json() ?? [];

            return [
                'found'  => true,
                'active' => $this->isActive($data),
                'data'   => $data,
            ];
        } catch (RequestException $e) {
            Log::warning('ConsultarService: erro HTTP na consulta CRP', [
                'uf'      => $uf,
                'numero'  => $numero,
                'status'  => $e->response?->status(),
                'message' => $e->getMessage(),
            ]);

            throw new ConsultarServiceException(
                'Falha ao consultar CRP na Consultar.IO.',
                (int) ($e->response?->status() ?? 0),
                $e
            );
        } catch (\Throwable $e) {
            Log::error('ConsultarService: erro inesperado', [
                'uf'      => $uf,
                'numero'  => $numero,
                'message' => $e->getMessage(),
            ]);

            throw new ConsultarServiceException('Falha ao consultar CRP na Consultar.IO.', 0, $e);
        }
    }

    private function isActive(array $data): bool
    {
        $candidates = [
            $data['situacao']    ?? null,
            $data['situation']   ?? null,
            $data['status']      ?? null,
            $data['data']['situacao']  ?? null,
            $data['data']['status']    ?? null,
        ];

        foreach ($candidates as $value) {
            if (is_string($value) && $value !== '') {
                $normalized = mb_strtolower(trim($value));

                return in_array($normalized, ['ativo', 'active', 'regular'], true);
            }
        }

        return true;
    }
}
