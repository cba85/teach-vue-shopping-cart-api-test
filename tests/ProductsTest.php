<?php

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client as GuzzleHttp;

final class ProductsTest extends TestCase
{

    public function testProducts()
    {
        global $argv;

        $client = new GuzzleHttp([
            'base_uri' => $argv[2]
        ]);
        $response = $client->get('/api/products');
        $this->assertEquals(200, $response->getStatusCode());
        $products = json_decode($response->getBody());
        $this->assertIsArray($products);
    }
}
