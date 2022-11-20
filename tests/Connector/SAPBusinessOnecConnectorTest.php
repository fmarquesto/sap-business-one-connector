<?php

namespace Tests\Connector;

use fmarquesto\SapBusinessOneConnector\Connector\ISAPClient;
use fmarquesto\SapBusinessOneConnector\Connector\SAPBusinessOneConnector;
use fmarquesto\SapBusinessOneConnector\Connector\SAPClient;
use PHPUnit\Framework\TestCase;
use Mockery as m;

class SAPBusinessOnecConnectorTest extends TestCase
{
    private string $cookieLoginHeader = 'ROUTEID=.node1; path=/b1s';
    private ISAPClient $client;

    public function setUp(): void
    {
        $this->client = m::mock(SAPClient::class);
        $this->client->shouldReceive('login')->andReturn(['Set-Cookie'=>[$this->cookieLoginHeader]]);
        $this->client->shouldReceive('logout');
    }

    public function testCreatFromEnvironmentVariables()
    {
        $client = m::spy(SAPClient::class);
        $connector = new SAPBusinessOneConnector($client);
        $client->shouldReceive('login')->withArgs(function($uri, $options){
            $this->assertEquals($options['json'], [
                'UserName' =>$_ENV['SAP_USER'],
                'Password' => $_ENV['SAP_PASS'],
                'CompanyDB' => $_ENV['SAP_DB'],
                'Language' => "23"
            ]);

            return true;
        })->andReturn(['Set-Cookie'=>[$this->cookieLoginHeader]]);
        $connector->execute('GET','URL');
    }

    public function testCreateFromOptions()
    {
        $client = m::spy(SAPClient::class);
        $connector = new SAPBusinessOneConnector($client,
            [
                'SAP_USER'=>'USEROPT',
                'SAP_PASS'=>'PASSOPT',
                'SAP_DB'=>'DBOPT',
                'SAP_HOST'=>'HOSTOPT',
                'SAP_PORT'=>'1000'
            ]
        );

        $client->shouldReceive('login')->withArgs(function($uri, $options){
            $this->assertEquals($options['json'], [
                'UserName' =>'USEROPT',
                'Password' => 'PASSOPT',
                'CompanyDB' => 'DBOPT',
                'Language' => "23"
            ]);

            return true;
        })->andReturn(['Set-Cookie'=>[$this->cookieLoginHeader]]);
        $connector->execute('GET','URL');
    }

    public function testExecuteHasLoginHeaders()
    {
        $this->client->shouldReceive('execute')->withArgs(function($method, $url, $options){

            $this->assertArrayHasKey('headers', $options);
            $this->assertArrayHasKey('Cookie', $options['headers']);
            $this->assertEquals($this->cookieLoginHeader, $options['headers']['Cookie']);

            return true;
        })->andReturn([]);
        $connector = new SAPBusinessOneConnector($this->client);
        $result = $connector->execute('GET','endpoint');
        $this->assertIsArray($result);
    }

    public function testExecuteWithPageSize()
    {
        $this->client->shouldReceive('login')->andReturn(['Set-Cookie'=>[$this->cookieLoginHeader]]);
        $this->client->shouldReceive('execute')->withArgs(function($method, $url, $options){

            $this->assertArrayHasKey('Prefer', $options['headers']);
            $this->assertEquals('odata.maxpagesize=100', $options['headers']['Prefer']);

            return true;
        });
        $connector = new SAPBusinessOneConnector($this->client);
        $connector->setPageSizeHeader(100);
        $connector->execute('GET','URL');
    }
}
