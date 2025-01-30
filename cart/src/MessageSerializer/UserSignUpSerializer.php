<?php

namespace App\MessageSerializer;

use App\Message\UserSignUp;

final class UserSignUpSerializer extends MessageSerializer
{
    protected string $deserializeType = UserSignUp::class;
}
