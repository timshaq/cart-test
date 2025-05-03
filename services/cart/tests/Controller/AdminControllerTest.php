<?php

namespace App\Tests\Controller;

use App\DataFixtures\OrderFixtures;
use App\DataFixtures\ProductFixtures;
use App\DataFixtures\UserFixtures;
use App\Entity\Order;
use App\Entity\User;
use App\Tests\WebTestCaseWithFixtures;
use Symfony\Component\HttpFoundation\Response;

final class AdminControllerTest extends WebTestCaseWithFixtures
{
    protected array $fixturesDependencies = [
        UserFixtures::class,
        ProductFixtures::class,
        OrderFixtures::class
    ];

    private const STATUS = 'AdminControllerTest';

    public function testUnauthorized(): void
    {
        $this->client->request('POST', '/admin/order/1/status', [
            'status' => self::STATUS,
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    public function testAuthorizedWithoutAdminRole(): void
    {
        $user = $this->referenceRepository->getReference(
            UserFixtures::REFERENCE_USER,
            User::class
        );
        $this->assertNotNull($user);

        $order = $this->referenceRepository->getReference(
            OrderFixtures::REFERENCE_ORDER,
            Order::class
        );
        $this->assertNotNull($order);

        $this->client->loginUser($user);
        $this->client->request(
            'POST',
            sprintf('/admin/order/%d/status', $order->getId()),
            ['status' => self::STATUS]
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testAuthorizedWithAdminRole(): void
    {
        $user = $this->referenceRepository->getReference(
            UserFixtures::REFERENCE_USER_WITH_ADMIN_ROLE,
            User::class
        );
        $this->assertNotNull($user);

        $order = $this->referenceRepository->getReference(
            OrderFixtures::REFERENCE_ORDER,
            Order::class
        );
        $this->assertNotNull($order);

        $this->client->loginUser($user);
        $this->client->request(
            'POST',
            sprintf('/admin/order/%d/status', $order->getId()),
            ['status' => self::STATUS]
        );

        $this->assertResponseIsSuccessful();

        $this->assertTrue($order->getStatus() === self::STATUS);
    }

    public function testUndefinedOrder(): void
    {
        $user = $this->referenceRepository->getReference(
            UserFixtures::REFERENCE_USER_WITH_ADMIN_ROLE,
            User::class
        );
        $this->assertNotNull($user);

        $this->client->loginUser($user);
        $this->client->request(
            'POST',
            sprintf('/admin/order/0/status'),
            ['status' => self::STATUS]
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }

    public function testOrderEmptyQueryParameter(): void
    {

        $this->requestForTestQueryParameters(
            '',
            self::STATUS,
        );
    }

    public function testOrderNullQueryParameter(): void
    {
        $this->requestForTestQueryParameters(
            'null',
            self::STATUS
        );
    }

    public function testOrderBoolQueryParameter(): void
    {
        $this->requestForTestQueryParameters(
            'false',
            self::STATUS
        );
    }

    public function testOrderJsonQueryParameter(): void
    {
        $this->requestForTestQueryParameters(
            '[]',
            self::STATUS
        );
    }

    public function testStatusEmptyQueryParameter(): void
    {
        $order = $this->referenceRepository->getReference(
            OrderFixtures::REFERENCE_ORDER,
            Order::class
        );
        $this->assertNotNull($order);

        $this->requestForTestQueryParameters($order->getId(), '', Response::HTTP_BAD_REQUEST);
    }

    private function requestForTestQueryParameters(
        mixed $orderId = null,
        mixed $status = null,
        int   $assertResponseStatusCode = Response::HTTP_NOT_FOUND
    ): void
    {
        $user = $this->referenceRepository->getReference(
            UserFixtures::REFERENCE_USER_WITH_ADMIN_ROLE,
            User::class
        );
        $this->assertNotNull($user);

        $this->client->loginUser($user);
        $this->client->request(
            'POST',
            sprintf('/admin/order/%s/status', $orderId), [
                'status' => $status,
            ]
        );

        $this->assertResponseStatusCodeSame($assertResponseStatusCode);
    }
}
