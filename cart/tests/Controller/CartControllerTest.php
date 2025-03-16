<?php

namespace App\Tests\Controller;

use App\DataFixtures\ProductFixtures;
use App\DataFixtures\UserFixtures;
use App\Entity\CartItem;
use App\Entity\Product;
use App\Entity\User;
use App\Tests\WebTestCaseWithFixtures;
use Symfony\Component\HttpFoundation\Response;

final class CartControllerTest extends WebTestCaseWithFixtures
{
    protected array $fixturesDependencies = [
        UserFixtures::class,
        ProductFixtures::class
    ];

    public function testUnauthorized(): void
    {
        $this->client->request('POST', '/cart/add/1');
        self::assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    public function testUnauthorizedUndefinedProductId(): void
    {
        $this->client->request('POST', '/cart/add/-1');
        self::assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    public function testUnauthorizedEmptyProductId(): void
    {
        $this->client->request('POST', '/cart/add/');
        self::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testUnauthorizedNullProductId(): void
    {
        $this->client->request('POST', '/cart/add/null');
        self::assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    public function testAuthorizedUndefinedProductId(): void
    {
        $user = $this->referenceRepository->getReference(
            UserFixtures::REFERENCE_USER,
            User::class
        );
        $this->assertNotNull($user);

        $this->client->loginUser($user);
        $this->client->request('POST', '/cart/add/-1');

        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }

    public function testAuthorizedEmptyProductId(): void
    {
        $user = $this->referenceRepository->getReference(
            UserFixtures::REFERENCE_USER,
            User::class
        );
        $this->assertNotNull($user);

        $this->client->loginUser($user);
        $this->client->request('POST', '/cart/add/');

        self::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testAuthorizedNullProductId(): void
    {
        $user = $this->referenceRepository->getReference(
            UserFixtures::REFERENCE_USER,
            User::class
        );
        $this->assertNotNull($user);

        $this->client->loginUser($user);
        $this->client->request('POST', '/cart/add/null');

        self::assertResponseStatusCodeSame(Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function testAuthorizedPositiveCase(): void
    {
        $user = $this->referenceRepository->getReference(
            UserFixtures::REFERENCE_USER,
            User::class
        );
        $this->assertNotNull($user);

        $this->client->loginUser($user);

        $references = $this->referenceRepository->getReferencesByClass();
        $products = $references[Product::class];
        $product = $products[array_key_first($products)];
        $this->assertNotNull($product);

        $this->client->request('POST', sprintf('/cart/add/%d', $product->getId()));
        $this->assertResponseIsSuccessful();

        /** @var CartItem $cartItemsProduct */
        $cartItemsProduct = $user->getCartItems()->first();
        $this->assertNotNull($cartItemsProduct);
        $this->assertTrue($cartItemsProduct->getProduct()->getId() === $product->getId());
    }
}
