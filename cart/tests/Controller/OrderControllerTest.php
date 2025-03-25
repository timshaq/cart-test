<?php

namespace App\Tests\Controller;

use App\DataFixtures\CartItemFixtures;
use App\DataFixtures\OrderFixtures;
use App\DataFixtures\UserFixtures;
use App\Entity\CartItem;
use App\Entity\Order;
use App\Entity\OrderProduct;
use App\Entity\User;
use App\Repository\CartItemRepository;
use App\Tests\WebTestCaseWithFixtures;
use Symfony\Component\HttpFoundation\Response;

final class OrderControllerTest extends WebTestCaseWithFixtures
{
    protected array $fixturesDependencies = [
        UserFixtures::class,
        CartItemFixtures::class,
        OrderFixtures::class
    ];

    public function testUnauthorizedOrderList(): void
    {
        $this->client->request('GET', '/orders');
        self::assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    public function testAuthorizedOrderList(): void
    {
        $user = $this->referenceRepository->getReference(
            UserFixtures::REFERENCE_USER,
            User::class
        );

        $this->assertNotNull($user);

        $this->client->loginUser($user);
        $this->client->request('GET', '/orders');

        $this->assertResponseIsSuccessful();
    }

    public function testUnauthorizedCreateOrder(): void
    {
        $this->client->request('POST', '/order', [
            'deliveryType' => 'courier',
            'deliveryAddress' => 'Russia, Moscow',
            'kladrId' => 77
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    public function testAuthorizedCreateOrder(): void
    {
        $user = $this->referenceRepository->getReference(
            UserFixtures::REFERENCE_USER_WITH_CART_ITEM,
            User::class
        );
        $this->assertNotNull($user);

        $this->assertEquals(1, $user->getCartItems()->count());
        /** @var CartItem $cartItem */
        $cartItem = $user->getCartItems()->first();
        $cartItemId = $cartItem->getId();
        $cartItemProductId = $cartItem->getProduct()->getId();

        $this->client->loginUser($user);
        $this->client->request('POST', '/order', self::getValidOrderCreatePayload());
        $this->assertResponseIsSuccessful();

        /** @var Order $order */
        $order = $user->getOrders()->first();
        /** @var OrderProduct $orderProduct */
        $orderProduct = $order->getProducts()->first();
        $this->assertTrue($orderProduct->getProductId() === $cartItemProductId);

        $this->assertSame(0, $user->getCartItems()->count());

        /** @var CartItemRepository $cartItemRepository */
        $cartItemRepository = static::getContainer()->get(CartItemRepository::class);
        $this->assertNull($cartItemRepository->find($cartItemId));
    }

    public function testAuthorizedCreateOrderWithSelfDeliveryDeliveryType(): void
    {
        $user = $this->referenceRepository->getReference(
            UserFixtures::REFERENCE_USER_WITH_CART_ITEM,
            User::class
        );
        $this->assertNotNull($user);

        $this->assertEquals(1, $user->getCartItems()->count());
        /** @var CartItem $cartItem */
        $cartItem = $user->getCartItems()->first();

        $this->client->loginUser($user);
        $this->client->request('POST', '/order', [
            'deliveryType' => 'selfdelivery',
            'deliveryAddress' => 'Russia, Moscow',
            'kladrId' => 77
        ]);
        $this->assertResponseIsSuccessful();

        /** @var Order $order */
        $order = $user->getOrders()->first();
        /** @var OrderProduct $orderProduct */
        $orderProduct = $order->getProducts()->first();
        $this->assertTrue($orderProduct->getProductId() === $cartItem->getProduct()->getId());
    }

    public function testAuthorizedCreateOrderWithEmptyDeliveryType(): void
    {
        $user = $this->referenceRepository->getReference(
            UserFixtures::REFERENCE_USER_WITH_CART_ITEM,
            User::class
        );
        $this->assertNotNull($user);

        $this->assertEquals(1, $user->getCartItems()->count());

        $this->client->loginUser($user);
        $this->client->request('POST', '/order', [
            'deliveryType' => '',
            'deliveryAddress' => 'Russia, Moscow',
            'kladrId' => 77
        ]);
        $this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testAuthorizedCreateOrderWithNullDeliveryType(): void
    {
        $user = $this->referenceRepository->getReference(
            UserFixtures::REFERENCE_USER_WITH_CART_ITEM,
            User::class
        );
        $this->assertNotNull($user);

        $this->assertEquals(1, $user->getCartItems()->count());

        $this->client->loginUser($user);
        $this->client->request('POST', '/order', [
            'deliveryType' => null,
            'deliveryAddress' => 'Russia, Moscow',
            'kladrId' => 77
        ]);
        $this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testAuthorizedCreateOrderWithoutDeliveryType(): void
    {
        $user = $this->referenceRepository->getReference(
            UserFixtures::REFERENCE_USER_WITH_CART_ITEM,
            User::class
        );
        $this->assertNotNull($user);

        $this->assertEquals(1, $user->getCartItems()->count());

        $this->client->loginUser($user);
        $this->client->request('POST', '/order', [
            'deliveryAddress' => 'Russia, Moscow',
            'kladrId' => 77
        ]);
        $this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testAuthorizedCreateOrderWithEmptyDeliveryAddress(): void
    {
        $user = $this->referenceRepository->getReference(
            UserFixtures::REFERENCE_USER_WITH_CART_ITEM,
            User::class
        );
        $this->assertNotNull($user);

        $this->assertEquals(1, $user->getCartItems()->count());
        /** @var CartItem $cartItem */
        $cartItem = $user->getCartItems()->first();

        $this->client->loginUser($user);
        $this->client->request('POST', '/order', [
            'deliveryType' => 'courier',
            'deliveryAddress' => '',
            'kladrId' => 77
        ]);
        $this->assertResponseIsSuccessful();

        /** @var Order $order */
        $order = $user->getOrders()->first();
        /** @var OrderProduct $orderProduct */
        $orderProduct = $order->getProducts()->first();
        $this->assertTrue($orderProduct->getProductId() === $cartItem->getProduct()->getId());
    }

    public function testAuthorizedCreateOrderWithNullDeliveryAddress(): void
    {
        $user = $this->referenceRepository->getReference(
            UserFixtures::REFERENCE_USER_WITH_CART_ITEM,
            User::class
        );
        $this->assertNotNull($user);

        $this->assertEquals(1, $user->getCartItems()->count());
        /** @var CartItem $cartItem */
        $cartItem = $user->getCartItems()->first();

        $this->client->loginUser($user);
        $this->client->request('POST', '/order', [
            'deliveryType' => 'courier',
            'deliveryAddress' => null,
            'kladrId' => 77
        ]);
        $this->assertResponseIsSuccessful();

        /** @var Order $order */
        $order = $user->getOrders()->first();
        /** @var OrderProduct $orderProduct */
        $orderProduct = $order->getProducts()->first();
        $this->assertTrue($orderProduct->getProductId() === $cartItem->getProduct()->getId());
    }

    public function testAuthorizedCreateOrderWithoutDeliveryAddress(): void
    {
        $user = $this->referenceRepository->getReference(
            UserFixtures::REFERENCE_USER_WITH_CART_ITEM,
            User::class
        );
        $this->assertNotNull($user);

        $this->assertEquals(1, $user->getCartItems()->count());
        /** @var CartItem $cartItem */
        $cartItem = $user->getCartItems()->first();

        $this->client->loginUser($user);
        $this->client->request('POST', '/order', [
            'deliveryType' => 'selfdelivery',
            'kladrId' => 77
        ]);
        $this->assertResponseIsSuccessful();

        /** @var Order $order */
        $order = $user->getOrders()->first();
        /** @var OrderProduct $orderProduct */
        $orderProduct = $order->getProducts()->first();
        $this->assertTrue($orderProduct->getProductId() === $cartItem->getProduct()->getId());
    }

    public function testAuthorizedCreateOrderWithEmptyKladrId(): void
    {
        $user = $this->referenceRepository->getReference(
            UserFixtures::REFERENCE_USER_WITH_CART_ITEM,
            User::class
        );
        $this->assertNotNull($user);

        $this->assertEquals(1, $user->getCartItems()->count());
        /** @var CartItem $cartItem */
        $cartItem = $user->getCartItems()->first();

        $this->client->loginUser($user);
        $this->client->request('POST', '/order', [
            'deliveryType' => 'courier',
            'deliveryAddress' => 'Russia, Moscow',
            'kladrId' => ''
        ]);
        $this->assertResponseIsSuccessful();

        /** @var Order $order */
        $order = $user->getOrders()->first();
        /** @var OrderProduct $orderProduct */
        $orderProduct = $order->getProducts()->first();
        $this->assertTrue($orderProduct->getProductId() === $cartItem->getProduct()->getId());
    }

    public function testAuthorizedCreateOrderWithNullKladrId(): void
    {
        $user = $this->referenceRepository->getReference(
            UserFixtures::REFERENCE_USER_WITH_CART_ITEM,
            User::class
        );
        $this->assertNotNull($user);

        $this->assertEquals(1, $user->getCartItems()->count());
        /** @var CartItem $cartItem */
        $cartItem = $user->getCartItems()->first();

        $this->client->loginUser($user);
        $this->client->request('POST', '/order', [
            'deliveryType' => 'courier',
            'deliveryAddress' => 'Russia, Moscow',
            'kladrId' => null
        ]);
        $this->assertResponseIsSuccessful();

        /** @var Order $order */
        $order = $user->getOrders()->first();
        /** @var OrderProduct $orderProduct */
        $orderProduct = $order->getProducts()->first();
        $this->assertTrue($orderProduct->getProductId() === $cartItem->getProduct()->getId());
    }

    public function testAuthorizedCreateOrderWithoutKladrId(): void
    {
        $user = $this->referenceRepository->getReference(
            UserFixtures::REFERENCE_USER_WITH_CART_ITEM,
            User::class
        );
        $this->assertNotNull($user);

        $this->assertEquals(1, $user->getCartItems()->count());
        /** @var CartItem $cartItem */
        $cartItem = $user->getCartItems()->first();

        $this->client->loginUser($user);
        $this->client->request('POST', '/order', [
            'deliveryType' => 'selfdelivery',
            'deliveryAddress' => 'Russia, Moscow',
        ]);
        $this->assertResponseIsSuccessful();

        /** @var Order $order */
        $order = $user->getOrders()->first();
        /** @var OrderProduct $orderProduct */
        $orderProduct = $order->getProducts()->first();
        $this->assertTrue($orderProduct->getProductId() === $cartItem->getProduct()->getId());
    }

    public function testAuthorizedCreateOrderWithEmptyCart(): void
    {
        $user = $this->referenceRepository->getReference(
            UserFixtures::REFERENCE_USER,
            User::class
        );
        $this->assertNotNull($user);

        $this->assertEquals(0, $user->getCartItems()->count());

        $this->client->loginUser($user);
        $this->client->request('POST', '/order', self::getValidOrderCreatePayload());
        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }

    public function testAuthorizedCreateOrderWithLargeCart(): void
    {
        $user = $this->referenceRepository->getReference(
            UserFixtures::REFERENCE_USER_WITH_LARGE_CART,
            User::class
        );
        $this->assertNotNull($user);

        $this->assertGreaterThan(Order::CART_MAX_ITEMS, count($user->getCartItems()));

        $this->client->loginUser($user);
        $this->client->request('POST', '/order', self::getValidOrderCreatePayload());
        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }

    private static function getValidOrderCreatePayload(): array
    {
        return [
            'deliveryType' => 'courier',
            'deliveryAddress' => 'Russia, Moscow',
            'kladrId' => 77
        ];
    }

    public function testUnauthorizedOrderStatus(): void
    {
        $user = $this->referenceRepository->getReference(
            UserFixtures::REFERENCE_USER_WITH_ORDER,
            User::class
        );
        $this->assertNotNull($user);

        $this->assertEquals(1, $user->getOrders()->count());
        $order = $user->getOrders()->first();

        $this->client->request('GET', sprintf('/order/%d/status', $order->getId()));

        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    public function testAuthorizedOrderStatus(): void
    {
        $user = $this->referenceRepository->getReference(
            UserFixtures::REFERENCE_USER_WITH_ORDER,
            User::class
        );
        $this->assertNotNull($user);

        $this->assertEquals(1, $user->getOrders()->count());
        $order = $user->getOrders()->first();

        $this->client->loginUser($user);
        $this->client->request('GET', sprintf('/order/%d/status', $order->getId()));

        $this->assertResponseIsSuccessful();
    }
}
