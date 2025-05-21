<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class BlogPostApiTest extends WebTestCase
{
    private $client;

    protected function setUp(): void
    {
        // Initialize the client using static::createClient()
        $this->client = static::createClient();
    }

    private function assertValidJsonResponse(): array
    {
        $responseContent = $this->client->getResponse()->getContent();
        $decodedContent = json_decode($responseContent, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->fail('Invalid JSON response: ' . $responseContent);
        }

        return $decodedContent;
    }

    private function authenticate(): string
    {
        // Ensure the test user exists in the test database
        $this->client->request('POST', '/api/login_check', [
            'json' => [
                'username' => 'testuser',
                'password' => 'testpassword'
            ]
        ]);

        // Assert the response is successful
        $this->assertResponseIsSuccessful();

        // Validate the response contains a valid JWT token
        $data = $this->assertValidJsonResponse();
        $this->assertArrayHasKey('token', $data, 'JWT token not found in the response.');

        return $data['token'];
    }

    public function testListBlogPosts(): void
    {
        $this->client->request('GET', '/api/blogposts');

        $this->assertResponseIsSuccessful();
        $this->assertValidJsonResponse();
    }

    public function testShowBlogPost(): void
    {
        $this->client->request('GET', '/api/blogposts/1');

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertValidJsonResponse();
    }

    public function testCreateBlogPostWithoutAuthentication(): void
    {
        $this->client->request('POST', '/api/blogposts', [
            'json' => [
                'title' => 'Test Blog Post',
                'content' => 'This is a test blog post.'
            ]
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    public function testCreateBlogPostWithAuthentication(): void
    {
        $token = $this->authenticate();

        $this->client->request('POST', '/api/blogposts', [
            'json' => [
                'title' => 'Test Blog Post',
                'content' => 'This is a test blog post.'
            ],
            'headers' => [
                'Authorization' => 'Bearer ' . $token
            ]
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertValidJsonResponse();
    }

    public function testUpdateBlogPostWithoutAuthentication(): void
    {
        $this->client->request('PUT', '/api/blogposts/1', [
            'json' => [
                'title' => 'Updated Title'
            ]
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    public function testUpdateBlogPostWithAuthentication(): void
    {
        $token = $this->authenticate();

        $this->client->request('PUT', '/api/blogposts/1', [
            'json' => [
                'title' => 'Updated Title'
            ],
            'headers' => [
                'Authorization' => 'Bearer ' . $token
            ]
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testDeleteBlogPostWithoutAuthentication(): void
    {
        $this->client->request('DELETE', '/api/blogposts/1');

        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    public function testDeleteBlogPostWithAuthentication(): void
    {
        $token = $this->authenticate();

        $this->client->request('DELETE', '/api/blogposts/1', [
            'headers' => [
                'Authorization' => 'Bearer ' . $token
            ]
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }

    public function testValidationErrors(): void
    {
        $token = $this->authenticate();

        $this->client->request('POST', '/api/blogposts', [
            'json' => [
                'content' => 'Missing title field.'
            ],
            'headers' => [
                'Authorization' => 'Bearer ' . $token
            ]
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }

    public function testNotFound(): void
    {
        $this->client->request('GET', '/api/blogposts/999999');

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }
}
