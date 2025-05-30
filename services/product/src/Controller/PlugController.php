<?php

namespace App\Controller;

use App\Service\ProductMessageGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;

class PlugController extends AbstractController
{
    /**
     * @throws ExceptionInterface
     */
    #[Route(
        '/plug/product/generate/{count}',
        name: 'generate-product',
        requirements: ['count' => '\d+'],
        methods: ['GET']
    )]
    public function generateNewProduct(
        int                     $count,
        ProductMessageGenerator $productGenerator,
        MessageBusInterface     $messageBus
    ): JsonResponse
    {
        $productMessages = $productGenerator->generateProducts($count);

        foreach ($productMessages as $productMessage) {
            $messageBus->dispatch($productMessage);
            sleep(1/10);
        }

        return $this->json($productMessages);
    }

}
