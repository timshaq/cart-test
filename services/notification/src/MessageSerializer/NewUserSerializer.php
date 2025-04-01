<?php

namespace App\MessageSerializer;

use App\Message\Consume\NewUserMessage;

final class NewUserSerializer extends MessageSerializer
{
    protected string $deserializeType = NewUserMessage::class;
}
