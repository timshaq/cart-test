<?php

namespace App\MessageSerializer;

use App\Message\Product;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;

final class ProductSerializer implements SerializerInterface
{
    public function decode(array $encodedEnvelope): Envelope
    {
        // todo: handle Throwable
        $record = json_decode($encodedEnvelope['body'], true);
        return new Envelope(Product::fromArray($record));
    }

    public function encode(Envelope $envelope): array
    {
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
