<?php

namespace App\MessageSerializer;

use App\Message\Produce\UserSignUp;

final class UserSignUpSerializer extends MessageSerializer
{
    protected string $deserializeType = UserSignUp::class;
}
