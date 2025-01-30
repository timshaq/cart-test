<?php

namespace App\MessageSerializer;

use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;
use Symfony\Component\Serializer\Serializer;

class MessageSerializer
{
    public function __construct(protected Serializer $serializer)
    {
    }
}
