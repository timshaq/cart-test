<?php

namespace App\Tests\Controller;

use App\DataFixtures\UserFixtures;
use App\Entity\User;
use App\Tests\WebTestCaseWithFixtures;
use Symfony\Component\HttpFoundation\Response;

final class ReferenceControllerTest extends WebTestCaseWithFixtures
{
    protected array $fixturesDependencies = [
        UserFixtures::class,
    ];

    public function testGetOrderStatusUnauthorized(): void
    {
        $this->client->request('GET', '/reference/order/status');

        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    public function testGetOrderStatusAuthorized(): void
    {
        $user = $this->referenceRepository->getReference(
            UserFixtures::REFERENCE_USER,
            User::class
        );
        $this->assertNotNull($user);

        $this->client->loginUser($user);
        $this->client->request('GET', '/reference/order/status');

        $this->assertResponseIsSuccessful();
    }

//    public function testGetOrderStatusAuthorizedWithApiKey(): void
//    {
//        // todo: write
//    }
}
