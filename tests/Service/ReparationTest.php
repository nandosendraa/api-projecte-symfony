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

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();
        $encoder = $this->client->getContainer()->get(JWTEncoderInterface::class);
        $this->client = static::createClient([], ["auth_bearer" => $encoder->encode(["username" => "user", "role" => ["ROLE_USER"]])]);
    }
    public function testGetCollectionReturnsValidData(): void
    {
        $response = static::createClient()->request('GET', '/api/reparations',
            [ "headers" => ["Accept: application/json"]]
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
}