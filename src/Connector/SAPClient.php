<?php

namespace fmarquesto\SapBusinessOneConnector\Connector;

use GuzzleHttp\Client;

class SAPClient implements ISAPClient
{
    private Client $client;
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function login(string $uri, array $options):array
    {
        $response = $this->client->post($uri, $options);
        return $response->getHeaders();
    }

    public function logout(string $uri, array $options):void
    {
        $this->client->post($uri,$options);
    }

    public function execute(string $method, string $uri, array $options): array
    {
        $response = $this->client->request($method, $uri, $options);
        return json_decode($response->getBody()->__toString(), true) ?? [];
    }
}
