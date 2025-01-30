<?php

namespace App\MessageSerializer;

use App\Message\UserSignUp;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;

final class UserSignUpSerializer extends MessageSerializer implements SerializerInterface
{
    public function decode(array $encodedEnvelope): Envelope
    {
        $product = $this->serializer->deserialize($encodedEnvelope['body'], UserSignUp::class, 'json');
        $product->setMessageData($encodedEnvelope);

        return new Envelope($product);
    }

    public function encode(Envelope $envelope): array
    {
        /** @var UserSignUp $event */
        $event = $envelope->getMessage();
        return [
            'key' => $event->getMessageKey(),
            'headers' => $event->getMessageHeaders(),
            'body' => $this->serializer->serialize($event, 'json')
        ];
    }

}
