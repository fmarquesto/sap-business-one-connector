<?php

namespace fmarquesto\SapBusinessOneConnector;

use Dotenv\Dotenv;

class Client implements InteractsWithSAP
{
    private readonly \GuzzleHttp\Client $client;

    private readonly ConnectionData $connectionData;

    private array $sessionHeader = [];

    private array $pageSizeHeader = [];

    public function __construct(\GuzzleHttp\Client $client = null, array $connectionData = [])
    {
        $this->client = $client ?? new \GuzzleHttp\Client(['verify' => false, 'http_errors' => false, 'headers' => ['Accept' => 'application/json' ]]);

        $this->connectionData = empty($connectionConfig) ?
            $this->loadCredentialsFromEnvironment() :
            $this->loadCredentialsFromArray($connectionData);

    }

    private function loadCredentialsFromEnvironment(): ConnectionData
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
        $dotenv->safeLoad();
        $dotenv->required(['SAP_HOST', 'SAP_PORT', 'SAP_USER', 'SAP_PASS', 'SAP_DB']);

        return $this->loadCredentialsFromArray($_ENV);
    }

    private function loadCredentialsFromArray(array $data): ConnectionData
    {
        return new ConnectionData(
            $data['SAP_HOST'],
            $data['SAP_PORT'],
            $data['SAP_DB'],
            $data['SAP_USER'],
            $data['SAP_PASS'],
        );
    }

    public function execute(QueryBuilder $queryBuilder, string $method = 'GET'): Response
    {
        try {
            $this->login();
            $response = $this->client->request($method, $this->baseUrl() . $queryBuilder->buildUrl(), [
                'json' => $queryBuilder->params,
                'headers' => array_merge($this->sessionHeader, ['Content-Type' => 'application/json'], $this->pageSizeHeader)
            ]);
            return new Response($response);
        } finally {
            $this->logout();
            $this->resetHeaders();
        }

    }

    private function login(): bool
    {
        $response = $this->client->post($this->baseUrl() . 'Login', ['json' => $this->connectionData->loginData()]);
        $headers = $response->getHeaders();
        $this->sessionHeader['Cookie'] = implode(';', $headers['Set-Cookie']);

        return $response->getStatusCode();
    }

    private function baseUrl(): string
    {
        return rtrim($this->connectionData->host, '/') . ':' . $this->connectionData->port . '/b1s/v1/';
    }

    private function logout(): bool
    {
        $response = $this->client->post($this->baseUrl() . 'Logout', ['headers' => $this->sessionHeader]);

        return $response->getStatusCode();
    }

    private function resetHeaders(): void
    {
        $this->sessionHeader = [];
        $this->pageSizeHeader = [];
    }
}
