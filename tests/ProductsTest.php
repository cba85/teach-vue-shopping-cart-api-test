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
        $products = json_decode($response->getBody());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertIsArray($products);
        $this->assertNotEmpty($products);
        $this->assertObjectHasAttribute('id', $products[0]);
        $this->assertObjectHasAttribute('created_at', $products[0]);
        $this->assertObjectHasAttribute('updated_at', $products[0]);
        $this->assertObjectHasAttribute('name', $products[0]);
        $this->assertObjectHasAttribute('price', $products[0]);
        $this->assertObjectHasAttribute('description', $products[0]);
    }
}
