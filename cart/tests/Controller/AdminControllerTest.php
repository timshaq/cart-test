<?php

namespace App\Tests\Controller;

use App\DataFixtures\OrderFixtures;
use App\DataFixtures\ProductFixtures;
use App\DataFixtures\UserFixtures;
use App\Entity\Constant;
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
    protected array $excludedTables = [Constant::TABLE_NAME];

    public function testUnauthorized(): void
    {
        $client = static::createClient();
        $client->request('GET', '/admin');

        self::assertResponseIsSuccessful();
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
            sprintf(
                '/admin/order/%d/status/%d',
                $order->getId(),
                Constant::ORDER_STATUS_PAID_ID
            )
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
            sprintf(
                '/admin/order/%d/status/%d',
                $order->getId(),
                Constant::ORDER_STATUS_PAID_ID
            )
        );

        $this->assertResponseIsSuccessful();
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
            sprintf(
                '/admin/order/0/status/%d',
                Constant::ORDER_STATUS_PAID_ID
            )
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }

    public function testOrderEmptyQueryParameter(): void
    {

        $this->requestForTestQueryParameters(
            '',
            Constant::ORDER_STATUS_PAID_ID
        );
    }

    public function testOrderNullQueryParameter(): void
    {
        $this->requestForTestQueryParameters(
            'null',
            Constant::ORDER_STATUS_PAID_ID
        );
    }

    public function testOrderBoolQueryParameter(): void
    {
        $this->requestForTestQueryParameters(
            'false',
            Constant::ORDER_STATUS_PAID_ID
        );
    }

    public function testOrderJsonQueryParameter(): void
    {
        $this->requestForTestQueryParameters(
            '[]',
            Constant::ORDER_STATUS_PAID_ID
        );
    }

    public function testUndefinedStatus(): void
    {
        $order = $this->referenceRepository->getReference(
            OrderFixtures::REFERENCE_ORDER,
            Order::class
        );
        $this->assertNotNull($order);

        $this->requestForTestQueryParameters(
            $order->getId(),
            '0',
            Response::HTTP_BAD_REQUEST
        );
    }

    public function testStatusEmptyQueryParameter(): void
    {
        $order = $this->referenceRepository->getReference(
            OrderFixtures::REFERENCE_ORDER,
            Order::class
        );
        $this->assertNotNull($order);

        $this->requestForTestQueryParameters($order->getId(), '');
    }

    public function testStatusNullQueryParameter(): void
    {
        $order = $this->referenceRepository->getReference(
            OrderFixtures::REFERENCE_ORDER,
            Order::class
        );
        $this->assertNotNull($order);

        $this->requestForTestQueryParameters($order->getId(), 'null');
    }

    public function testStatusBoolQueryParameter(): void
    {
        $order = $this->referenceRepository->getReference(
            OrderFixtures::REFERENCE_ORDER,
            Order::class
        );
        $this->assertNotNull($order);

        $this->requestForTestQueryParameters($order->getId(), 'true');
    }

    public function testStatusJsonQueryParameter(): void
    {
        $order = $this->referenceRepository->getReference(
            OrderFixtures::REFERENCE_ORDER,
            Order::class
        );
        $this->assertNotNull($order);

        $this->requestForTestQueryParameters($order->getId(), '[]');
    }

    private function requestForTestQueryParameters(
        mixed $orderId = null,
        mixed $statusId = null,
        int $assertResponseStatusCode = Response::HTTP_NOT_FOUND
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
            sprintf(
                '/admin/order/%s/status/%s',
                $orderId,
                $statusId
            )
        );

        $this->assertResponseStatusCodeSame($assertResponseStatusCode);
    }
}
