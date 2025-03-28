<?php

namespace App\MessageSerializer;

use App\Message\Message;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\Recipient\Recipient;
use Symfony\Component\Serializer\Serializer;
use Throwable;

class MessageSerializer implements SerializerInterface
{
    protected string $deserializeType = Message::class;

    public function __construct(protected Serializer $serializer, private NotifierInterface $notifier)
    {
    }

    public function decode(array $encodedEnvelope): Envelope
    {
        try {
            $product = $this->serializer->deserialize($encodedEnvelope['body'], $this->deserializeType, 'json');
            $product->setMessageData($encodedEnvelope);
            return new Envelope($product);
        } catch (Throwable $throwable) {
            $message = sprintf(
                "Consumer error \(%s\).\n\nError:\n%s\n\nKafka Message:\n%s",
                str_replace("\\", '/', $this->deserializeType),
                $throwable->getMessage(),
                json_encode($encodedEnvelope, JSON_THROW_ON_ERROR)
            );
            $notification = (new Notification($message));
            $this->notifier->send($notification);
            throw $throwable;
        }
    }

    public function encode(Envelope $envelope): array
    {
        /** @var Message $event */
        $event = $envelope->getMessage();
        return [
            'key' => $event->getMessageKey(),
            'headers' => $event->getMessageHeaders(),
            'timestamp' => $event->getMessageTimestamp(),
            'offset' => $event->getMessageOffset(),
            'body' => $this->serializer->serialize($event, 'json')
        ];
    }
}
