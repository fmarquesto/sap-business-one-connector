<?php

namespace fmarquesto\SapBusinessOneConnector\Connector;

use fmarquesto\SapBusinessOneConnector\Repositories\IRepository;

interface ISAPBusinessOneConnector
{
    public function execute(string $method, string $url, array $data = []): array;
}
