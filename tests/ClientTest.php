<?php

namespace Tests;

use fmarquesto\SapBusinessOneConnector\Client;
use fmarquesto\SapBusinessOneConnector\QueryBuilder;
use \fmarquesto\SapBusinessOneConnector\Response;
use PHPUnit\Framework\TestCase;
use Mockery as m;

class ClientTest extends TestCase
{
    private \GuzzleHttp\Client|m\MockInterface $client;
    public function setUp(): void
    {
        $this->client = m::mock(\GuzzleHttp\Client::class);
        $this->client->shouldReceive('post')->withArgs(function ($url, $headers) {
            return $url == 'http://fake:30000/b1s/v1/Login';
        })->andReturn(new \GuzzleHttp\Psr7\Response(200, [
            'Set-Cookie' => $sessionHeader = 'ROUTEID=.node1; path=/b1s'
        ]));

        $this->client->shouldReceive('post')->withArgs(function ($url, $headers) use ($sessionHeader) {
            return $url === 'http://fake:30000/b1s/v1/Logout' && $headers['headers']['Cookie'] === $sessionHeader;
        });
    }
    public function test_execute_returns_response()
    {
        $client = new Client($this->client);
        $this->client->shouldReceive('request')
            ->with('GET', 'http://fake:30000/b1s/v1/Items', [
                'json' => [],
                'headers' => ['Cookie' => 'ROUTEID=.node1; path=/b1s', 'Content-Type' => 'application/json']
            ])
            ->andReturn(new \GuzzleHttp\Psr7\Response(200, [], json_encode(['data' => 'test'])));
        $response = $client->execute(new QueryBuilder('Items'));

        $this->assertInstanceOf(Response::class, $response);
    }

}
