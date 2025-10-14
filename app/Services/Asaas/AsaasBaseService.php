<?php

namespace App\Services\Asaas;

use App\Exceptions\AsaasException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

abstract class AsaasBaseService
{
    /**
     * Chave de API do Asaas.
     *
     * @var string
     */
    protected string $apiKey;

    /**
     * URL base da API do Asaas.
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
        $this->apiKey        = config('services.asaas.api_key');
        $this->baseUrl       = config('services.asaas.base_url');
        $this->timeout       = config('services.asaas.timeout', 30);
        $this->retryAttempts = config('services.asaas.retry_attempts', 3);

        if (!$this->apiKey) {
            throw new AsaasException('API Key do Asaas não configurado.');
        }
    }

    /**
     * Realiza uma requisição HTTP para a API do Asaas.
     *
     * @param string $method Método HTTP (get, post, put, delete).
     * @param string $endpoint Endpoint da API.
     * @param array $data Dados a serem enviados na requisição.
     * @return array Resposta da API.
     * @throws AsaasException
     */
    protected function makeRequest(string $method, string $endpoint, array $data = []): array
    {
        $url = $this->baseUrl . $endpoint;

        try {
            $response = Http::withHeaders([
                'access_token' => $this->apiKey,
                'Content-Type' => 'application/json',
                'Accept'       => 'application/json',
                'User-Agent'   => 'Laravel-Asaas-Integration/1.0',
            ])
            ->timeout($this->timeout)
            ->retry($this->retryAttempts, 1000)
            ->{$method}($url, $data);

            return $this->handleResponse($response);

        } catch (AsaasException $e) {
            // Re-lança AsaasException sem modificar (mantém code e errors originais)
            throw $e;
        } catch (\Exception $e) {
            // Apenas erros de rede/timeout são encapsulados
            throw new AsaasException('Erro de comunicação com Asaas:: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Manipula a resposta da API do Asaas.
     *
     * @param Response $response
     * @return array
     * @throws AsaasException
     */
    protected function handleResponse(Response $response): array
    {
        $data = $response->json();

        if ($response->successful()) {
            return $data ?? [];
        }

        $errors  = $data['errors'] ?? [];
        $message = $data['message'] ?? 'Erro na requisição para Asaas.';

        throw new AsaasException($message, $response->status(), $errors);
    }

    /**
     * Métodos HTTP para interagir com a API do Asaas.
     * Cada método corresponde a um tipo de requisição.
     */
    protected function get(string $endpoint, array $params = []): array
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
