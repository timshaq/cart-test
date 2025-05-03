<?php

namespace App\Controller;

use App\Entity\CartItem;
use App\Entity\User;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CartController extends CommonController
{
    #[Route('/cart/add/{productId}', name: 'cart_add_item', methods: ['POST'])]
    public function addCartItem(
        EntityManagerInterface $entityManager,
        ProductRepository $productRepository,
        int $productId
    ): Response
    {
        $product = $productRepository->find($productId);
        if (!$product) {
            throw new BadRequestException('Undefined product');
        }

        /** @var User $user */
        $user = $this->getUser();
        $cartHasTheProduct = $user->getCartItems()
            ->findFirst(static fn($key, CartItem $item) => $item->getProductId() === $productId);

        if ($cartHasTheProduct) {
            return new Response();
        }

        $entityManager->beginTransaction();
        try {
            $newCartItem = new CartItem();
            $newCartItem->setUser($user);
            $newCartItem->setProduct($product);

            $user->getCartItems()->add($newCartItem);

            $entityManager->persist($user);
            $entityManager->flush();
            $entityManager->commit();
        } catch (\Throwable) {
            $entityManager->rollback();
            throw new \RuntimeException('Can\'t add product to cart');
        }

        return new Response();
    }
}
