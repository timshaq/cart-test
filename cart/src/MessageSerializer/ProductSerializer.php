<?php

namespace App\MessageSerializer;

use App\Message\Product;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;

final class ProductSerializer extends MessageSerializer implements SerializerInterface
{
    public function decode(array $encodedEnvelope): Envelope
    {
        dump('decode');
//        dump($encodedEnvelope);
        // todo: handle Throwable
        $product = $this->serializer->deserialize($encodedEnvelope['body'], Product::class, 'json');
        $product->setMessageData($encodedEnvelope);

        return new Envelope($product);
    }

    public function encode(Envelope $envelope): array
    {
        dump('encode');
        /** @var Product $event */
        $event = $envelope->getMessage();
        return [
            'key' => $event->getMessageKey(),
            'headers' => $event->getMessageHeaders(),
            'body' => $this->serializer->serialize($event, 'json')
        ];
    }

}
