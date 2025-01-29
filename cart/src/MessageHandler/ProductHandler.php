<?php

namespace App\MessageHandler;

use App\Message\Product;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class ProductHandler
{
    public function __invoke(Product $message): void
    {
        dump('handler');
        dump($message);
    }
}
