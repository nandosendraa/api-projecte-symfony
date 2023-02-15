<?php

namespace App\Tests\Service;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Reparation;

class ReparationTest extends ApiTestCase
{
    public function testGetCollectionReturnsValidData(): void
    {
        $response = static::createClient()->request('GET', '/api/reparations',
            [ "headers" => ["Accept: application/json"]]
        );

        $this->assertResponseIsSuccessful();
        //$this->assertMatchesResourceCollectionJsonSchema(Reparation::class);

        $this->assertCount(30, $response->toArray());

    }
}