<?php

namespace App\MessageHandler;

use App\Message\Consume\NewUserMessage;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class NewUserHandler
{
    public function __invoke(NewUserMessage $message): void
    {
        // logic
    }
}
