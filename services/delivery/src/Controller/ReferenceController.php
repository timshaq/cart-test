<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class ReferenceController extends AbstractController
{
    #[Route('/reference/order/status', name: 'reference-status', methods: ['GET'])]
    public function getOrderStatuses(): JsonResponse
    {
        // todo
        return $this->json('ok');
    }

}
