<?php

namespace App\MessageHandler;

use App\Message\Consume\Product\ProductMessage;
use App\Repository\ProductRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class ProductHandler
{
    public function __construct(private ProductRepository $productRepository)
    {
    }
    public function __invoke(ProductMessage $message): void
    {
        $this->productRepository->createOrUpdateByMessage($message);
    }
}
