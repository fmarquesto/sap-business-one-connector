<?php

namespace fmarquesto\SapBusinessOneConnector\Connector;

interface ISAPClient
{
    function login(string $uri, array $options): array;
    function logout(string $uri, array $options): void;
    function execute(string $method, string $uri, array $options): array;
}
