<?php

namespace App\Services\Pagarme;

use App\Exceptions\PagarmeException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

abstract class PagarmeBaseService
{
    /**
     * Chave de API do Pagar.me.
     *
     * @var string
     */
    protected string $apiKey;

    /**
     * URL base da API do Pagar.me.
     *
     * @var string
     */
    protected string $baseUrl;

    /**
     * Tempo limite para requisições em segundos.
     *
     * @var int
     */
    protected int $timeout;

    /**
     * Número de tentativas de repetição em caso de falha.
     *
     * @var int
     */
    protected int $retryAttempts;

    public function __construct()
    {
        $this->apiKey        = config('services.pagarme.api_key');
        $this->baseUrl       = config('services.pagarme.base_url');
        $this->timeout       = config('services.pagarme.timeout');
        $this->retryAttempts = config('services.pagarme.retry_attempts');

        if (!$this->apiKey) {
            throw new PagarmeException('API Key do Pagar.me não configurado.');
        }
    }

    /**
     * Realiza uma requisição HTTP para a API do Pagar.me.
     *
     * @param string $method Método HTTP (get, post, put, delete).
     * @param string $endpoint Endpoint da API.
     * @param array $data Dados a serem enviados na requisição.
     * @return array Resposta da API.
     * @throws PagarmeException
     */
    protected function makeRequest(string $method, string $endpoint, array $data = []): array
    {
        $url = $this->baseUrl . $endpoint;

        try {
            $response = Http::withHeaders([
                'Accept'       => 'application/json',
                'Authorization' => "Basic " . base64_encode("{$this->apiKey}:"),
                'Content-Type' => 'application/json',
                'User-Agent'   => 'Laravel-Pagarme-Integration/1.0',
            ])
            ->timeout($this->timeout)
            ->retry($this->retryAttempts, 1000)
            ->{$method}($url, $data);

            return $this->handleResponse($response);

        } catch (\Exception $e) {
            throw new PagarmeException('Erro de comunicação com Pagar.me: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Manipula a resposta da API do Pagar.me.
     *
     * @param Response $response
     * @return array
     * @throws PagarmeException
     */
    protected function handleResponse(Response $response): array
    {
        $data = $response->json();

        if ($response->successful()) {
            return $data ?? [];
        }

        $errors  = $data['errors'] ?? [];
        $message = $data['message'] ?? 'Erro na requisição para Pagar.me.';

        throw new PagarmeException($message, $response->status(), $errors);
    }

    /**
     * Métodos HTTP para interagir com a API do Pagar.me.
     * Cada método corresponde a um tipo de requisição.
     */
    protected function get(string $endpoint, array $params = [], $cached = true): array
    {
        $queryString = !empty($params) ? '?' . http_build_query($params) : '';

        return $this->makeRequest('get', $endpoint . $queryString);
    }

    protected function post(string $endpoint, array $data = []): array
    {
        return $this->makeRequest('post', $endpoint, $data);
    }

    protected function put(string $endpoint, array $data = []): array
    {
        return $this->makeRequest('put', $endpoint, $data);
    }

    protected function delete(string $endpoint): array
    {
        return $this->makeRequest('delete', $endpoint);
    }
}
