<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class ProductController extends AbstractController
{
    #[Route('/products', name: 'products', methods: ['GET'])]
    public function products(ProductRepository $repository): JsonResponse
    {
        // todo: pagination
        return $this->json($repository->findAll());
    }
}
