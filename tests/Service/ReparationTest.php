<?php

namespace App\Tests\Service;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Symfony\Bundle\Test\Client;
use App\Entity\Reparation;
use App\Repository\UserRepository;
use DateTime;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;

class ReparationTest extends ApiTestCase
{
    private Client $client;
    private string $token;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();
        $this->token = $this->createToken("user", "user");
        $encoder = $this->client->getContainer()->get(JWTEncoderInterface::class);
        $this->client = static::createClient([], ["auth_bearer" => $encoder->encode(["username" => "user", "role" => ["ROLE_USER"]])]);
    }

    protected function createToken($username = 'user', $password = 'password'): string
    {
        $client = static::createClient();
        $response = $client->request('POST', '/login', [
            'headers' => ['Content-Type: application/json'],
            'json' => [
                'username' => $username,
                'password' => $password,
            ],
        ]);

        $data = $response->toArray();
        return $data['token'];
    }

    public function testGetCollectionReturnsValidData(): void
    {
        $response = static::createClient()->request('GET', '/api/reparations',
            [
                'auth_bearer' => $this->token,
                "headers" => ["Accept: application/json"]]
        );

        $this->assertResponseIsSuccessful();
        //$this->assertMatchesResourceCollectionJsonSchema(Reparation::class);

        $this->assertCount(30, $response->toArray());

    }

    public function testPostValidData():void
    {
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneBy(["username" => "user"]);

        $date = new DateTime();
        $dateStr = $date->format('c');

        $response = $this->client->request('POST', '/api/reparations',
            [
                'auth_bearer' => $this->token,
                'headers' => ["Accept: application/json"],
                'json' => [
                    'name' => 'Prova',
                    'description' => 'descripcio de prova',
                    'status' => 'llest',
                    'date' => $dateStr,
                    'owner' => '/api/users/' . $user->getId(),
                ]
            ]
        );

        $this->assertJsonContains([
            'name' => 'Prova',
            'description' => 'descripcio de prova',
            'status' => 'llest',
            'date' => $dateStr,
            'owner' => ["username" => "user"],
        ]);

        $this->assertResponseStatusCodeSame(201);
        //$this->assertMatchesResourceItemJsonSchema(Reparation::class);
    }
    public function testPostInvalidData(): void
    {

        //$this->expectException(UnexpectedValueException::class);
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneBy(["username" => "user"]);
        $date = new DateTime();
        $dateStr = $date->format('c');

        $response = $this->client->request('POST', '/api/reparations',
            [
                'auth_bearer' => $this->token,
                'headers' => ["Accept: application/json"],
                'json' => [
                    'name' => 2,
                    'description' => 2,
                    'status' => 'llest',
                    'date' => $dateStr,
                    'owner' => 1,
                ]
            ]
        );

        $date = new DateTime();
        $dateStr = $date->format('c');

        $this->assertResponseStatusCodeSame(400);
    }

    public function testPostNoData(): void
    {
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneBy(["username" => "user"]);

        $response = $this->client->request('POST', '/api/tweets',
            [
                'auth_bearer' => $this->token,
                'headers' => ["Accept: application/json"],
                'json' => [
                ]
            ]
        );

        //dump($response);
        $date = new DateTime();
        $dateStr = $date->format('c');

        $this->assertResponseStatusCodeSame(422);
        //$this->assertMatchesResourceItemJsonSchema(Tweet::class);

        $this->assertJsonContains([
            'violations' => [
                [
                    "propertyPath" => "name",
                    "message" => "This value should not be blank."
                ],
                [
                    "propertyPath" => "description",
                    "message" => "This value should not be blank."
                ],
                [
                    "propertyPath" => "status",
                    "message" => "This value should not be blank."
                ],
                [
                    "propertyPath" => "date",
                    "message" => "This value should not be blank."
                ],
                [
                    "propertyPath" => "owner",
                    "message" => "This value should not be blank."
                ]

            ]
        ]);
    }
}