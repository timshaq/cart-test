<?php

namespace App\Tests\Controller;

use App\DataFixtures\UserFixtures;
use App\Entity\User;
use App\Tests\WebTestCaseWithFixtures;
use Symfony\Component\Finder\Finder;
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

    public function testGetReportFile(): void
    {
        $projectDir = static::getContainer()->getParameter('kernel.project_dir');

        $finder = new Finder();
        $finder->in($projectDir . '/reports');
        $this->assertTrue($finder->hasResults());

        $reportId = null;
        foreach ($finder as $file) {
            $reportId = str_replace('.jsonl', '', $file->getFilename());
            break;
        }
        $this->assertNotEmpty($reportId);

        $apiKey = static::getContainer()->getParameter('secret.integration');
        $this->client->setServerParameter('HTTP_api-key', $apiKey);

        $this->client->request('GET','/api/integration/report/' . $reportId);

        $this->assertResponseHeaderSame('Content-Type', 'application/x-ndjson');
        $this->assertResponseIsSuccessful();
    }

    public function testGetReportFileWithUndefinedId(): void
    {
        $apiKey = static::getContainer()->getParameter('secret.integration');

        $this->client->setServerParameter('HTTP_api-key', $apiKey);

        $this->client->request('GET','/api/integration/report/asd');

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }

    public function testGetReportFileWithEmptyId(): void
    {
        $apiKey = static::getContainer()->getParameter('secret.integration');

        $this->client->setServerParameter('HTTP_api-key', $apiKey);

        $this->client->request('GET','/api/integration/report/');

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }
}
