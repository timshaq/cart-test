<?php

namespace App\Controller;

use App\Entity\Constant;
use App\Repository\ConstantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class ReferenceController extends AbstractController
{
    public function __construct(
        private ConstantRepository $repository
    )
    {
    }

    private function byTypeId(int $id): array
    {
        return $this->repository->findBy(['typeId' => $id]);
    }

    #[Route('/reference/order/status', name: 'reference', methods: ['GET'])]
    public function orderStatus(): JsonResponse
    {
        return $this->json($this->byTypeId(Constant::TYPE_ID_ORDER_STATUS));
    }
}
