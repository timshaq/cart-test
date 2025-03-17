<?php

namespace App\Tests\Controller;

use App\DataFixtures\UserFixtures;
use App\Entity\User;
use App\Tests\WebTestCaseWithFixtures;
use Symfony\Component\HttpFoundation\Response;

final class ReportControllerTest extends WebTestCaseWithFixtures
{
    protected array $fixturesDependencies = [
        UserFixtures::class
    ];

    public function testUnauthorized(): void
    {
        $this->client->request('GET', '/api/integration/report/orders/completed');
        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    public function testAuthorizedWithoutAdminRole(): void
    {
        $user = $this->referenceRepository->getReference(
            UserFixtures::REFERENCE_USER,
            User::class
        );
        $this->assertNotNull($user);

        $this->client->loginUser($user);

        $this->client->request('GET', '/api/integration/report/orders/completed');
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }
    public function testAuthorizedWithAdminRole(): void
    {
        $user = $this->referenceRepository->getReference(
            UserFixtures::REFERENCE_USER_WITH_ADMIN_ROLE,
            User::class
        );
        $this->assertNotNull($user);

        $this->client->loginUser($user);

        $this->client->request('GET', '/api/integration/report/orders/completed');
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testAuthorizedWithApiKey(): void
    {
        $apiKey = static::getContainer()->getParameter('secret.integration');

        $this->client->setServerParameter('HTTP_api-key', $apiKey);

        $this->client->request('GET','/api/integration/report/orders/completed');

        $this->assertResponseIsSuccessful();
    }
    // todo: make
//    public function testInvalidFrom(): void
//    {
//    }
//    public function testInvalidTo(): void
//    {
//    }
//    public function testGetFileReport(): void
//    {
//    }
}
