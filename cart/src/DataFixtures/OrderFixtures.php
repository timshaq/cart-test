<?php

namespace App\DataFixtures;

use App\Dto\NewOrderDto;
use App\Entity\CartItem;
use App\Entity\Constant;
use App\Entity\Order;
use App\Entity\OrderProduct;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ObjectManager;

class OrderFixtures extends Fixture
{
    public const REFERENCE_ORDER = 'REFERENCE_ORDER';

    public function load(ObjectManager $manager): void
    {
        $user = $this->getReference(
            UserFixtures::REFERENCE_USER_WITH_ORDER,
            User::class
        );

        $references = $this->referenceRepository->getReferencesByClass();
        $products = $references[Product::class];
        /** @var Product $product */
        $product = $products[array_key_first($products)];

        if (!($product instanceof Product)) {
            throw new \RuntimeException('Invalid product');
        }

        $newCartItem = new CartItem();
        $newCartItem->setUser($user);
        $newCartItem->setProduct($product);

        $user->getCartItems()->add($newCartItem);

        $newOrderDto = new NewOrderDto('courier');

        $order = new Order();
        $orderCost = 0;
        foreach ($user->getCartItems() as $cartItem) {
            /** @var CartItem $cartItem */
            $orderCost += $cartItem->getProduct()->getCost();

            $cartItemProduct = $cartItem->getProduct();

            $orderProduct = new OrderProduct();
            $orderProduct->setCost($cartItemProduct->getCost());
            $orderProduct->setProductId($cartItemProduct->getId());
            $orderProduct->setMeasurement($cartItemProduct->getMeasurement());
            $orderProduct->setName($cartItemProduct->getName());
            $orderProduct->setTax($cartItemProduct->getTax());
            $orderProduct->setOrder($order);

            $order->getProducts()->add($orderProduct);
            $manager->persist($orderProduct);
        }

        $order->setStatus('оплачен и ждёт сборки');
        $order->setCost($orderCost);
        $order->setUser($user);
        $order->setDeliveryType($newOrderDto->getDeliveryType());
        $order->setDeliveryAddress($newOrderDto->getDeliveryAddress());
        $order->setKladrId($newOrderDto->getKladrId());

        $user->setCartItems(new ArrayCollection());

        $manager->persist($user);
        $manager->persist($order);
        $manager->persist($newCartItem);
        $manager->persist($product);
        $manager->flush();

        $this->addReference(self::REFERENCE_ORDER, $order);
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            ProductFixtures::class,
            CartItemFixtures::class,
        ];
    }
}
