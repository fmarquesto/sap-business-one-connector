<?php

namespace Tests;

use fmarquesto\SapBusinessOneConnector\Client;
use fmarquesto\SapBusinessOneConnector\QueryBuilder;
use fmarquesto\SapBusinessOneConnector\Resources;
use fmarquesto\SapBusinessOneConnector\Response;
use PHPUnit\Framework\TestCase;
use Mockery as m;

class ClientTest extends TestCase
{
    private \GuzzleHttp\Client|m\MockInterface $client;
    public function setUp(): void
    {
        $this->client = m::mock(\GuzzleHttp\Client::class);

        //Login and logout mock
        $this->client
            ->shouldreceive('post')
            ->with('http://fake:30000/b1s/v1/Login', ['json' => [ 'CompanyDB' => 'db', 'UserName' => 'user', 'Password' => 'pass', 'Language' => '23']])
            ->andReturn(new \GuzzleHttp\Psr7\Response(200, ['Set-Cookie' => $sessionHeader = 'ROUTEID=.node1; path=/b1s']))
            ->once();

        $this->client->shouldReceive('post')->with('http://fake:30000/b1s/v1/Logout', ['headers' => ['Cookie' => $sessionHeader]])
            ->andReturn(new \GuzzleHttp\Psr7\Response(200))
            ->once();
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
        $response = $client->execute(new QueryBuilder(Resources::Items));

        $this->assertInstanceOf(Response::class, $response);
    }

}
