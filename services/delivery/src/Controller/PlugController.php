<?php

namespace App\Controller;

use App\Message\Produce\UpdateOrderStatusMessage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
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
        '/plug/order/{orderId}/status/{statusId}',
        name: 'plug',
        requirements: ['orderId' => '\d+', 'statusId' => '\d+'],
        methods: ['POST']
    )]
    public function generateNewProduct(
        int $orderId,
        int $statusId,
        MessageBusInterface $messageBus
    ): Response
    {
        // todo
//        $message = new UpdateOrderStatusMessage($orderId, $statusId);
//        $messageBus->dispatch($message);
        return new Response();
    }
}
