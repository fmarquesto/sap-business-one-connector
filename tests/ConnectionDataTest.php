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
        $this->assertArrayHasKey('UserName', $credentials);
        $this->assertArrayHasKey('Password', $credentials);
    }
}
