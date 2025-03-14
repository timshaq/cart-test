<?php

namespace App\DataFixtures;

use App\Entity\CartItem;
use App\Entity\Order;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CartItemFixtures extends Fixture implements DependentFixtureInterface
{
    private ?ObjectManager $manager = null;

    public function load(ObjectManager $manager): void
    {
        $this->manager = $manager;
        $this->loadCartItemForUser();
        $this->loadCartItemsForUserWithLargeCart();
    }

    private function loadCartItemForUser(): void
    {
        $references = $this->referenceRepository->getReferencesByClass();
        $products = $references[Product::class];
        /** @var Product $product */
        $product = $products[array_key_first($products)];

        if (!($product instanceof Product)) {
            throw new \RuntimeException('Invalid product');
        }

        $user = $this->getReference(
            UserFixtures::REFERENCE_USER_WITH_CART_ITEM,
            User::class
        );

        $newCartItem = new CartItem();
        $newCartItem->setUser($user);
        $newCartItem->setProduct($product);

        $user->getCartItems()->add($newCartItem);

        $this->manager->persist($user);
        $this->manager->persist($product);
        $this->manager->flush();
    }

    private function loadCartItemsForUserWithLargeCart(): void
    {
        $references = $this->referenceRepository->getReferencesByClass();
        $products = $references[Product::class];
        if (count($products) < Order::CART_MAX_ITEMS) {
            throw new \RuntimeException('Need more product fixtures');
        }

        $user = $this->getReference(
            UserFixtures::REFERENCE_USER_WITH_LARGE_CART,
            User::class
        );

        foreach ($products as $product) {
            /** @var Product $product */
            $newCartItem = new CartItem();
            $newCartItem->setUser($user);
            $newCartItem->setProduct($product);

            $user->getCartItems()->add($newCartItem);
            $this->manager->persist($product);
        }

        $this->manager->persist($user);
        $this->manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            ProductFixtures::class
        ];
    }
}
