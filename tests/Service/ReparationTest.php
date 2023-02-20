<?php

namespace App\Tests\Service;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Reparation;
use App\Repository\UserRepository;

class ReparationTest extends ApiTestCase
{
    public function testGetCollectionReturnsValidData(): void
    {
        $response = static::createClient()->request('GET', '/api/reparations',
            [ "headers" => ["Accept: application/json"]]
        );

        $this->assertResponseIsSuccessful();
        $this->assertMatchesResourceCollectionJsonSchema(Reparation::class);

        $this->assertCount(30, $response->toArray());

    }

    public function testPostValidData():void
    {
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneBy(["username" => "user"]);

        $response = $this->client->request('POST', '/api/tweets',
            [
                'headers' => ["Accept: application/json"],
                'json' => [
                    'text' => 'Proves',
                    'author' => '/api/users/' . $user->getId(),
                ]
            ]
        );
    }
}