<?php

namespace App\MessageHandler;

use App\Message\Product;
use App\Repository\ProductRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class ProductHandler
{
    public function __construct(private ProductRepository $productRepository)
    {
    }
    public function __invoke(Product $message): void
    {
        dump('handler');
        $this->productRepository->createOrUpdateByMessage($message);
    }
}
