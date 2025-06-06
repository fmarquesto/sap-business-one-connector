<?php

namespace fmarquesto\SapBusinessOneConnector;

use Psr\Http\Message\ResponseInterface;

readonly class Response
{
    public function __construct(public ResponseInterface $response)
    {

    }
}