<?php

namespace App\Service;

use App\Entity\Constant;
use App\Entity\Order;
use App\Entity\OrderProduct;
use App\Entity\User;
use App\Message\Consume\UpdateOrderStatusMessage;
use App\Message\Produce\NewOrder\NewOrderMessage;
use App\Message\Produce\NewReport\NewReportMessage;
use App\Message\Produce\UserSignUpMessage;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Serializer\SerializerInterface;

readonly class KafkaProduceService
{
    private bool $isTestEnvironment;
    public function __construct(
        private MessageBusInterface $messageBus,
        private SerializerInterface $serializer,
        KernelInterface $kernel
    )
    {
        $this->isTestEnvironment = $kernel->getEnvironment() === 'test';
    }

    /**
     * @throws ExceptionInterface
     * @throws \Exception
     */
    public function sendNewUser(User $user): void
    {
        if ($this->isTestEnvironment) {
            return;
        }

        $message = new UserSignUpMessage(
            $user->getNotificationType()->getValue(),
            $user->getPhone(),
            $user->getEmail(),
            $user->getPromoId()
        );

        // todo: need only phone or email
        $this->messageBus->dispatch($message);
    }
}
