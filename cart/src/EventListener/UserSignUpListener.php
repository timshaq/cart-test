<?php

namespace App\EventListener;

use App\Event\UserSignUpEvent;
use App\Service\KafkaProduceService;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Messenger\Exception\ExceptionInterface;

final readonly class UserSignUpListener
{
    public function __construct(
        private KafkaProduceService $kafkaProduceService,
    )
    {
    }

    /**
     * @throws ExceptionInterface
     */
    #[AsEventListener(event: 'user.signup')]
    public function onUserSignUpEvent(UserSignUpEvent $event): void
    {
        $this->kafkaProduceService->sendNewUser($event->getUser());
    }
}
