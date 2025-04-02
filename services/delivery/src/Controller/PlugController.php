<?php

namespace App\Controller;

use App\Message\Produce\UpdateOrderStatusMessage;
use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;

class PlugController extends AbstractController
{
    /**
     * @throws ExceptionInterface
     * @throws \Exception
     */
    #[Route(
        '/plug/order/{orderId}/status',
        name: 'plug',
        requirements: ['orderId' => '\d+'],
        methods: ['POST']
    )]
    public function generateNewProduct(
        int $orderId,
        Request $request,
        OrderRepository $orderRepository,
        MessageBusInterface $messageBus
    ): Response
    {
        if (!$request->request->has('status') || empty($request->request->get('status'))) {
            throw new BadRequestHttpException('Status cannot be empty');
        }
        $status = $request->request->get('status');

        $order = $orderRepository->findOneBy(['id' => $orderId]);
        if (!$order) {
            throw new BadRequestHttpException('Order not found');
        }

        $message = new UpdateOrderStatusMessage($order->getCartId(), $status);
        $messageBus->dispatch($message);
        return new Response();
    }


    #[Route('/plug/orders', name: 'plug-orders', methods: ['GET'])]
    public function orders(OrderRepository $orderRepository): Response
    {
        return $this->json($orderRepository->findAll());
    }

}
