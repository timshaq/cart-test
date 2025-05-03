<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class ProductController extends CommonController
{
    #[Route('/products', name: 'products', methods: ['GET'])]
    public function products(Request $request, ProductRepository $repository): JsonResponse
    {
        $this->setPaginationParameters($request);
        $data = $repository->getProducts($this->getLimit(), $this->getOffset());

        return $this->getPaginationResponse($data);
    }
}
