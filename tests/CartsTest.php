<?php

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client as GuzzleHttp;

final class CartsTest extends TestCase
{
    protected $productId = 1;

    static public function setUpBeforeClass(): void
    {
        global $argv;

        // Empty cart
        $client = new GuzzleHttp([
            'base_uri' => $argv[2]
        ]);
        $response = $client->delete('/api/cart');
    }

    public function testGetEmptyCart()
    {
        global $argv;

        $client = new GuzzleHttp([
            'base_uri' => $argv[2]
        ]);

        $response = $client->get('/api/cart');
        $cart = json_decode($response->getBody());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertIsArray($cart);
        $this->assertEmpty($cart);
    }

    public function testStoreCartWithWrongParameters()
    {
        global $argv;

        $client = new GuzzleHttp([
            'base_uri' => $argv[2]
        ]);

        try {
            $client->post('/api/cart');
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $this->assertEquals(422, $e->getCode());
        }
    }

    public function testStoreCartWithIncorrectProduct()
    {
        global $argv;

        $client = new GuzzleHttp([
            'base_uri' => $argv[2]
        ]);

        $request = [
            'product_id' => '99999',
        ];

        try {
            $client->post('/api/cart', ['form_params' => $request]);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $this->assertEquals(404, $e->getCode());
        }
    }

    public function testStoreCart()
    {
        global $argv;

        $client = new GuzzleHttp([
            'base_uri' => $argv[2]
        ]);

        $request = [
            'product_id' => '1',
        ];

        $response = $client->post('/api/cart', ['form_params' => $request]);
        $data = json_decode($response->getBody());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertIsObject($data);
        $this->assertObjectHasAttribute('quantity', $data);
        $this->assertObjectHasAttribute('product', $data);
        $this->assertIsObject($data->product);
        $this->assertObjectHasAttribute('id', $data->product);
        $this->assertObjectHasAttribute('created_at', $data->product);
        $this->assertObjectHasAttribute('updated_at', $data->product);
        $this->assertObjectHasAttribute('name', $data->product);
        $this->assertObjectHasAttribute('price', $data->product);
        $this->assertObjectHasAttribute('description', $data->product);
    }

    public function testGetCart()
    {
        global $argv;

        $client = new GuzzleHttp([
            'base_uri' => $argv[2]
        ]);

        $response = $client->get('/api/cart');
        $cart = json_decode($response->getBody());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertIsArray($cart);
        $this->assertIsObject($cart[0]);
        $this->assertObjectHasAttribute('id', $cart[0]);
        $this->assertObjectHasAttribute('created_at', $cart[0]);
        $this->assertObjectHasAttribute('updated_at', $cart[0]);
        $this->assertObjectHasAttribute('product_id', $cart[0]);
        $this->assertObjectHasAttribute('quantity', $cart[0]);
        $this->assertObjectHasAttribute('product', $cart[0]);
        $this->assertIsObject($cart[0]->product);
        $this->assertObjectHasAttribute('id', $cart[0]->product);
        $this->assertObjectHasAttribute('created_at', $cart[0]->product);
        $this->assertObjectHasAttribute('updated_at', $cart[0]->product);
        $this->assertObjectHasAttribute('name', $cart[0]->product);
        $this->assertObjectHasAttribute('price', $cart[0]->product);
        $this->assertObjectHasAttribute('description', $cart[0]->product);
    }

    public function testRemoveIncorrectProductFromCart()
    {
        global $argv;

        $client = new GuzzleHttp([
            'base_uri' => $argv[2],
        ]);

        try {
            $client->delete('/api/cart/99999');
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $this->assertEquals(404, $e->getCode());
        }
    }

    public function testRemoveProductNotInCart()
    {
        global $argv;

        $client = new GuzzleHttp([
            'base_uri' => $argv[2],
        ]);

        try {
            $client->delete("/api/cart/3");
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $this->assertEquals(404, $e->getCode());
        }
    }

    public function testRemoveProductFromCart()
    {
        global $argv;

        $client = new GuzzleHttp([
            'base_uri' => $argv[2],
        ]);

        $response = $client->delete("/api/cart/{$this->productId}");
        $data = json_decode($response->getBody()->getContents());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEmpty($data);
    }

    public function testEmptyCart()
    {
        global $argv;

        $client = new GuzzleHttp([
            'base_uri' => $argv[2],
        ]);

        $response = $client->delete("/api/cart");
        $data = json_decode($response->getBody()->getContents());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEmpty($data);

        // Check if cart is really empty
        $response = $client->get('/api/cart');
        $cart = json_decode($response->getBody());
        $this->assertIsArray($cart);
        $this->assertEmpty($cart);
    }
}
