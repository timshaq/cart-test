<?php

namespace App\MessageHandler;

use App\Message\Consume\NewOrder\NewOrderMessage;
use App\Repository\OrderRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class NewOrderHandler
{
    public function __construct(private OrderRepository $repository)
    {
    }

    /**
     * @throws \JsonException
     */
    public function __invoke(NewOrderMessage $message): void
    {
        $this->repository->createNewOrder(
            $message->getOrderNum(),
            $message->getDeliveryType(),
            $message->getOrderItems(),
            $message->getDeliveryAddress()->getFullAddress(),
            $message->getDeliveryAddress()->getKladrId(),
        );
    }
}
