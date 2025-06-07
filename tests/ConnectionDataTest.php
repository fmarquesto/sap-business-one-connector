<?php
namespace Tests;

use fmarquesto\SapBusinessOneConnector\ConnectionData;
use PHPUnit\Framework\TestCase;

class ConnectionDataTest extends TestCase
{
    public function test_login_data_retrieves_connection_credentials()
    {
        $connectionData = new ConnectionData('someHost', 40000, 'someCompanyDB', 'someUserName', 'somePassword');

        $credentials = $connectionData->loginData();

        $this->assertArrayHasKey('CompanyDB', $credentials);
        $this->assertEquals('someCompanyDB', $credentials['CompanyDB']);
        $this->assertArrayHasKey('UserName', $credentials);
        $this->assertEquals('someUserName', $credentials['UserName']);
        $this->assertArrayHasKey('Password', $credentials);
        $this->assertEquals('somePassword', $credentials['Password']);
    }
}
