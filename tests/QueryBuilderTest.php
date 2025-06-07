<?php

namespace Tests;

use fmarquesto\SapBusinessOneConnector\QueryBuilder;
use PHPUnit\Framework\TestCase;

class QueryBuilderTest extends TestCase
{
    public function test_query_builder_build_url_with_select_and_filter(): void
    {
        $queryBuilder = new QueryBuilder('Items', 'ItemCode');
        $queryBuilder->addSelect('ItemCode', 'ItemName')->addFilter('ItemCode eq \'123\'');

        $url = $queryBuilder->buildUrl();
        $this->assertEquals(
            'Items(ItemCode)?$select=ItemCode,ItemName&$filter=' . rawurlencode('ItemCode eq \'123\''),
            $url
        );
    }

    public function test_query_builder_builds_url_with_key_without_query_params(): void
    {
        $queryBuilder = new QueryBuilder('Items', 'ItemCode');

        $url = $queryBuilder->buildUrl();

        $this->assertEquals('Items(ItemCode)', $url);
    }

    public function test_query_builder_builds_url_without_query_params(): void
    {
        $queryBuilder = new QueryBuilder('Items');

        $url = $queryBuilder->buildUrl();

        $this->assertEquals(rawurlencode('Items'), $url);
    }

}
