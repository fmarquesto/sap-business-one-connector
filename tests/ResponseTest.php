<?php

use fmarquesto\SapBusinessOneConnector\Response;
use PHPUnit\Framework\TestCase;

class ResponseTest extends TestCase
{
    public function test_has_next_page()
    {
        $response = new \GuzzleHttp\Psr7\Response(200, body: json_encode([
            'odata.nextLink' => 'https://example.com/next',
            'value' => []
        ]));
        $responseHasNextPage = new Response($response);

        $response = new \GuzzleHttp\Psr7\Response(200, body: json_encode([
            'value' => []
        ]));

        $responseDoesNotHaveNextPage = new Response($response);

        $this->assertTrue($responseHasNextPage->hasNextPage());
        $this->assertFalse($responseDoesNotHaveNextPage->hasNextPage());
    }

    public function test_success()
    {
        $response = new \GuzzleHttp\Psr7\Response(200);
        $responseSuccess = new Response($response);
        $response = new \GuzzleHttp\Psr7\Response(401);
        $responseNotSuccess = new Response($response);

        $this->assertTrue($responseSuccess->success());
        $this->assertFalse($responseNotSuccess->success());
    }

    public function test_array_body()
    {
        $response = new \GuzzleHttp\Psr7\Response(200, body: json_encode([
            'odata.nextLink' => 'https://example.com/next',
            'value' => ['item1', 'item2']
        ]));
        $responseSuccessObject = new Response($response);

        $expectedSuccessArray = [
            'odata.nextLink' => 'https://example.com/next',
            'value' => ['item1', 'item2']
        ];

        $response = new \GuzzleHttp\Psr7\Response(500);
        $responseError = new Response($response);
        $expectedErrorArray = [];

        $this->assertEquals($expectedErrorArray, $responseError->arrayBody());
        $this->assertEquals($expectedSuccessArray, $responseSuccessObject->arrayBody());
    }

    public function test_next_page()
    {
        $response = new \GuzzleHttp\Psr7\Response(200, body: json_encode([
            'odata.nextLink' => 'https://example.com/next',
            'value' => []
        ]));
        $responseHasNextPage = new Response($response);

        $response = new \GuzzleHttp\Psr7\Response(200, body: json_encode([
            'value' => []
        ]));
        $responseDoesNotHaveNextPage = new Response($response);

        $this->assertEquals('https://example.com/next', $responseHasNextPage->nextPage());
        $this->assertEquals('', $responseDoesNotHaveNextPage->nextPage());
    }
}
