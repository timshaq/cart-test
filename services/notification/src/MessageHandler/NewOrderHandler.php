<?php

namespace App\MessageHandler;

use App\Message\Consume\NewOrder\NewOrderMessage;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class NewOrderHandler
{
    public function __invoke(NewOrderMessage $message): void
    {
        // logic
    }
}
