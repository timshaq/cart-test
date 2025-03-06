<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class ReportController extends AbstractController
{
    #[Route('/report/product', name: 'report_product')]
    public function product(): JsonResponse
    {
        // todo: make
        /* produce to kafka
{
	"reportId": string, //"ebca4412-3965-45a8-bd36-4c1d1b768e7b"
	"result": string // "success", "fail"
	"detail": { // поле обязательно только в случае ошибки
		"error": string,
		"message": ?string
	}
}
         */
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/ReportController.php',
        ]);
    }
}
