<?php

namespace Tests\Connector;

use fmarquesto\SapBusinessOneConnector\Connector\SAPClient;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Mockery as m;
use Psr\Http\Message\StreamInterface;

class SAPClientTest extends TestCase
{
    private SAPClient $client;

    public function setUp(): void
    {
        $mockClient = m::spy(Client::class);
        $mockResponse = m::mock(ResponseInterface::class);
        $mockStream = m::mock(StreamInterface::class);

        $mockResponse->shouldReceive('getBody')->andReturn($mockStream);
        $mockResponse->shouldReceive('getHeaders')->andReturn([]);
        $mockClient->shouldReceive('post')->andReturn($mockResponse);
        $mockClient->shouldReceive('request')->andReturn($mockResponse);

        $this->client = new SAPClient($mockClient);
    }

    public function testClientLoginSuccess()
    {
        $res = $this->client->login('login',[]);
        $this->assertEmpty($res);
    }

    public function testClientLoginError()
    {
        $this->expectException(ConnectException::class);
        $mockClient = m::mock(Client::class);
        $mockClient->shouldReceive('post')->andThrow(new ConnectException("Error", m::spy(RequestInterface::class)));
        $client = new SAPClient($mockClient);
        $client->login('Login',[]);
    }

    public function testClientLogoutSuccess()
    {
        $this->assertNull($this->client->logout('logout',[]));
    }

    public function testExecuteRequest()
    {
        $res = $this->client->execute('GET', 'URI',[]);
        $this->assertEmpty($res);
    }
}
