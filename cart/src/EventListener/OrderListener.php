<?php

namespace App\EventListener;

use App\Event\OrderCreateEvent;
use App\Service\KafkaProduceService;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Messenger\Exception\ExceptionInterface;

final readonly class OrderListener
{
    public function __construct(
        private KafkaProduceService $kafkaProduceService,
    )
    {
    }

    /**
     * @throws \JsonException
     * @throws ExceptionInterface
     */
    #[AsEventListener(event: 'order.create')]
    public function onOrderCreate(OrderCreateEvent $event): void
    {
        $this->kafkaProduceService->sendNewOrder($event->getOrder());
    }
}
