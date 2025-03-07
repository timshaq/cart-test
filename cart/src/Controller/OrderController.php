<?php

namespace App\Controller;

use App\Dto\NewOrderDto;
use App\Entity\User;
use App\Event\OrderCreateEvent;
use App\Repository\OrderRepository;
use App\Service\OrderService;
use Psr\EventDispatcher\EventDispatcherInterface;
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
        EventDispatcherInterface $eventDispatcher,
    ): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        if ($user->getCartItems()->count() === 0) {
            throw new BadRequestException('Cart is empty');
        }

        if ($user->getCartItems()->count() > 20) {
            throw new BadRequestException('Cart has too many products');
        }

        try {
            $order = $orderService->createOrder($user, $newOrderDto);
            $eventDispatcher->dispatch(new OrderCreateEvent($order), 'order.create');
        } catch (\Throwable) {
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
