<?php

namespace App\MessageSerializer;

use App\Message\Product;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;
use Symfony\Component\Serializer\Serializer;

final class ProductSerializer implements SerializerInterface
{
    public function __construct(private Serializer $serializer)
    {
    }
    public function decode(array $encodedEnvelope): Envelope
    {
        // todo: handle Throwable
        $product = $this->serializer->deserialize($encodedEnvelope['body'], Product::class, 'json');
        return new Envelope($product);
    }

    public function encode(Envelope $envelope): array
    {
        // todo: write...
        return [];
//        $event = $envelope->getMessage();
//
//        return [
//            'key' => $event->getId(),
//            'headers' => [],
//            'body' => json_encode([
//                'id' => $event->getId(),
//                'name' => $event->getName(),
//                'description' => $event->getDescription(),
//            ]),
//        ];
    }

}
