<?php

namespace fmarquesto\SapBusinessOneConnector\Connector;

use GuzzleHttp\Client;

class SAPConnectorBuilder
{
    static function build(array $params = []): ISAPBusinessOneConnector
    {
        return new SAPBusinessOneConnector(
            new SAPClient(new Client(['verify' => false])),
            $params
        );
    }
}
