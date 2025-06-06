<?php

namespace fmarquesto\SapBusinessOneConnector;

interface InteractsWithSAP
{
    public function execute(QueryBuilder $queryBuilder, string $method = 'GET'): Response;
}
