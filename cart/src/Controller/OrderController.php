<?php

namespace App\Controller;

use App\Dto\NewOrderDto;
use App\Entity\Constant;
use App\Entity\OrderProduct;
use App\Entity\User;
use App\Message\Produce\NewOrder\NewOrder;
use App\Repository\OrderRepository;
use App\Service\OrderService;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;

final class OrderController extends CommonController
{
    #[Route('/order', name: 'order_create', methods: ['POST'])]
    public function create(
        #[MapRequestPayload] NewOrderDto $newOrderDto,
        MessageBusInterface $messageBus,
        OrderService $orderService
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

            // todo: refactor
            $messageOrderItems = [];

            foreach ($order->getProducts() as $product) {
                /** @var OrderProduct $product */
                // todo: change to deserialize
                $messageOrderItems[] = [
                    'name' => $product->getName(),
                    'cost' => $product->getCost(),
                    'additionalInfo' => null
                ];
            }

            $messageData = [
                'type' => $user->getNotificationType()->getValue(),
                'notificationType' => 'success_payment',
                'orderNum' => 'ORD_' . $order->getId(),
                'orderItems' => $messageOrderItems,
                'deliveryType' => $newOrderDto->getDeliveryType(),
                'deliveryAddress' => $newOrderDto->getDeliveryAddress()
            ];
            if ($user->getNotificationType()->getId() === Constant::NOTIFICATION_TYPE_SMS_ID) {
                $messageData['userPhone'] = $user->getPhone();
            }
            if ($user->getNotificationType()->getId() === Constant::NOTIFICATION_TYPE_EMAIL_ID) {
                $messageData['userEmail'] = $user->getEmail();
            }

            dump($messageData);
            $messageData = json_encode($messageData);
            $message = $this->serializer->deserialize($messageData, NewOrder::class, 'json');
            $messageBus->dispatch($message);
        } catch (\Throwable $e) {
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
}
