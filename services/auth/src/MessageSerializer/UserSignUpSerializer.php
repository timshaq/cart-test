<?php

namespace App\MessageSerializer;

use App\Message\Produce\UserSignUpMessage;

final class UserSignUpSerializer extends MessageSerializer
{
    protected string $deserializeType = UserSignUpMessage::class;
}
