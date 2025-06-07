<?php

namespace fmarquesto\SapBusinessOneConnector;

use Psr\Http\Message\ResponseInterface;

class Response
{
    private string $body;
    public function __construct(public readonly ResponseInterface $response)
    {

    }

    public function nextPage(): string
    {
        return $this->hasNextPage() ? $this->arrayBody()['odata.nextLink'] : '';
    }
    public function hasNextPage(): bool
    {
        return $this->success() && array_key_exists('odata.nextLink', $this->arrayBody());
    }

    public function success(): bool
    {
        return $this->response->getStatusCode() >= 200 && $this->response->getStatusCode() < 300;
    }

    public function arrayBody(): array
    {
        $this->body = $this->body ?? $this->response->getBody()->getContents();

        return json_decode($this->body, true) ?? [];
    }
}
