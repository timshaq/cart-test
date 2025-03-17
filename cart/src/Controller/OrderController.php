<?php

namespace App\Controller;

use App\Dto\NewOrderDto;
use App\Entity\Order;
use App\Entity\User;
use App\Repository\OrderRepository;
use App\Service\KafkaProduceService;
use App\Service\OrderService;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

final class OrderController extends CommonController
{
    #[Route('/order', name: 'order_create', methods: ['POST'])]
    public function create(
        #[MapRequestPayload] NewOrderDto $newOrderDto,
        OrderService $orderService,
        KafkaProduceService $kafkaProduceService,
        EntityManagerInterface $entityManager
    ): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        if ($user->getCartItems()->count() === 0) {
            throw new BadRequestException('Cart is empty');
        }

        if ($user->getCartItems()->count() > Order::CART_MAX_ITEMS) {
            throw new BadRequestException('Cart has too many products');
        }

        $entityManager->beginTransaction();
        try {
            $order = $orderService->createOrder($user, $newOrderDto);
            $entityManager->persist($order);

            foreach ($user->getCartItems() as $cartItem) {
                $entityManager->remove($cartItem);
            }

            $user->setCartItems(new ArrayCollection());
            $entityManager->persist($user);
            $entityManager->flush();

            $kafkaProduceService->sendNewOrder($order);

            $entityManager->commit();
        } catch (\Throwable) {
            $entityManager->rollback();
            throw new \RuntimeException('Can\'t create order');
        }

        return new Response();
    }

    #[Route('/order/{id}/status', name: 'order_status', methods: ['GET'])]
    public function status(int $id, OrderRepository $orderRepository): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $order = $orderRepository->find($id);
        if (!$order) {
            throw new BadRequestException('Order not found');
        }
        if ($order->getUser()->getId() !== $user->getId()) {
            throw new AccessDeniedException('Order not found');
        }

        return $this->json($order->getStatus()); // todo: serialize?
    }

    #[Route('/orders', name: 'orders', methods: ['GET'])]
    public function orders(Request $request, OrderRepository $orderRepository): Response
    {
        $this->setPaginationParameters($request);

        /** @var User $user */
        $user = $this->getUser();
        $data = $orderRepository->getUserOrders($user, $this->getLimit(), $this->getOffset());

        return $this->getPaginationResponse($data);
    }
}
