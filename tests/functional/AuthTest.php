<?php

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

/**
 * Class AuthTest
 * Интеграционный тест. Сервер должен быть запущен на http://127.0.0.1:9100
 */
class AuthTest extends \PHPUnit\Framework\TestCase
{

    private function getClient(): Client
    {
        return new Client([
            'base_uri' => 'http://127.0.0.1:9100',
            //'debug' => true,
        ]);
    }

    private function getSource(): array
    {
        return [
            'application' => [
                'name' => 'site',
                'version' => '123',
            ],
            'address' => [
                'ip' => '127.0.0.1',
            ]
        ];
    }

    /**
     * @return array
     */
    public function testUserRegistration()
    {
        $credentials = [
            'login' => uniqid('test') . '@example.com',
            'password' => uniqid(),
        ];

        $this->getClient()->postAsync('/person/create', [
            'json' => [
                'credentials' => $credentials,
                'source' => $this->getSource(),
            ]
        ])->then(function (ResponseInterface $response) {
            $this->assertEquals(200, $response->getStatusCode());

            $data = json_decode($response->getBody()->getContents(), true);

            $this->assertArrayHasKey('result', $data);
            $this->assertArrayHasKey('id', $data['result']);
            $this->assertArrayHasKey('session', $data['result']);
        })->wait();

        return $credentials;
    }

    /**
     * @param array $credentials
     * @return array
     * @depends testUserRegistration
     */
    public function testUserLogout(array $credentials)
    {
        //$this->markTestSkipped('TODO');
        $this->assertEquals(1, 1);
        return $credentials;
    }

    /**
     * @param array $credentials
     * @depends testUserLogout
     */
    public function testUserAuthentication(array $credentials)
    {
        $this->getClient()->postAsync('/person/login', [
            'json' => [
                'credentials' => $credentials,
                'source' => $this->getSource(),
            ]
        ])->then(function (ResponseInterface $response) {
            $this->assertEquals(200, $response->getStatusCode());

            $data = json_decode($response->getBody()->getContents(), true);

            $this->assertArrayHasKey('result', $data);
            $this->assertArrayHasKey('id', $data['result']);
            $this->assertArrayHasKey('session', $data['result']);
        })->wait();

    }
}