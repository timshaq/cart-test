<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ReportController extends AbstractController
{
    #[Route('/api/integration/report/orders/completed', name: 'report-orders-completed', methods: ['GET'])]
    public function completedOrders(): Response
    {
        $command = sprintf(
            'nohup php %s %s > /dev/null 2>&1 &',
            $this->getParameter('kernel.project_dir') . '/bin/console',
            'app:generate-report'
        );
        exec($command);

        return new Response();
    }

    // todo:
//    #[Route('/api/integration/report/{id}', name: 'report-orders-completed', methods: ['GET'])]
//    public function completedOrders(
//        Request $request,
//        ReportService $reportService,
//    ): Response
//    {
//
//        return $this->json($reportService->getReport());
//
//        return new Response();
//    }
}
