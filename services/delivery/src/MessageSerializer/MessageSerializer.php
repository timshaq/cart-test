<?php

namespace App\MessageSerializer;

use App\Message\Message;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;
use Symfony\Component\Serializer\Serializer;

class MessageSerializer implements SerializerInterface
{
    protected string $deserializeType = Message::class;

    public function __construct(protected Serializer $serializer)
    {
    }

    public function decode(array $encodedEnvelope): Envelope
    {
        $product = $this->serializer->deserialize($encodedEnvelope['body'], $this->deserializeType, 'json');
        $product->setMessageData($encodedEnvelope);
        return new Envelope($product);
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
