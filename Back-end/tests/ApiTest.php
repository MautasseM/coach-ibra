<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiTest extends WebTestCase
{
    public function testSampleApi(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/sample');

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(['message' => 'Hello from Symfony API!']);
    }

    public function testHomeApi(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/home');

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(['message' => 'Welcome to the API!']);
    }

    public function testContactApi(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/contact', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'message' => 'Hello!'
        ]));

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(['status' => 'success']);
    }
}
