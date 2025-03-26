<?php

namespace App\Controller;

use SplFileObject;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ReportController extends AbstractController
{
    #[Route('/api/integration/report/orders/completed', name: 'report-orders-completed', methods: ['GET'])]
    public function completedOrders(): Response
    {
        // todo: when@test
        $command = sprintf(
            'nohup php %s %s > /dev/null 2>&1 &',
            $this->getParameter('kernel.project_dir') . '/bin/console',
            'app:generate-report'
        );
        exec($command);

        return new Response();
    }

    #[Route('/api/integration/report/{id}', name: 'get-report-file', methods: ['GET'])]
    public function getReportFile(string $id): BinaryFileResponse
    {
        $filePath = sprintf(
            '%s/%s.jsonl',
            $this->getParameter('kernel.project_dir') . '/reports',
            $id
        );

        if (!file_exists($filePath)) {
            throw new BadRequestException('File not found');
        }

        $file = new SplFileObject($filePath);

        return new BinaryFileResponse($file->getFileInfo());
    }
}
