<?php

namespace App\Service;

use App\Entity\Constant;
use App\Entity\Order;
use App\Entity\OrderProduct;
use App\Entity\User;
use App\Message\Produce\NewOrder\NewOrderMessage;
use App\Message\Produce\UserSignUpMessage;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Serializer\SerializerInterface;

readonly class KafkaProduceService
{
    public function __construct(
        private MessageBusInterface $messageBus,
        private SerializerInterface $serializer,
    )
    {
    }

    /**
     * @throws ExceptionInterface
     * @throws \Exception
     */
    public function sendNewUser(User $user): void
    {
        $message = new UserSignUpMessage(
            $user->getNotificationType()->getValue(),
            $user->getPhone(),
            $user->getEmail(),
            $user->getPromoId()
        );

        // todo: need only phone or email
        $this->messageBus->dispatch($message);
    }

    /**
     * @throws \JsonException
     * @throws ExceptionInterface
     */
    public function sendNewOrder(Order $order): void
    {
        $messageOrderItems = $order->getProducts()->map(function (OrderProduct $product) {
            return [
                'name' => $product->getName(),
                'cost' => $product->getCost(),
                'additionalInfo' => null
            ];
        });

        $messageData = [
            'type' => $order->getUser()->getNotificationType()->getValue(),
            'notificationType' => 'success_payment',
            'orderNum' => 'ORD_' . $order->getId(),
            'orderItems' => $messageOrderItems,
            'deliveryType' => $order->getDeliveryType(),
            'deliveryAddress' => $order->getDeliveryAddress()
        ];
        if ($order->getUser()->getNotificationType()->getId() === Constant::NOTIFICATION_TYPE_SMS_ID) {
            $messageData['userPhone'] = $order->getUser()->getPhone();
        }
        if ($order->getUser()->getNotificationType()->getId() === Constant::NOTIFICATION_TYPE_EMAIL_ID) {
            $messageData['userEmail'] = $order->getUser()->getEmail();
        }

        $messageData = json_encode($messageData, JSON_THROW_ON_ERROR);
        $message = $this->serializer->deserialize($messageData, NewOrderMessage::class, 'json');
        $this->messageBus->dispatch($message);
    }
}
