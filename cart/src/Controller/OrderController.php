<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class OrderController extends AbstractController
{
    #[Route('/order', name: 'create', methods: ['POST'])]
    public function create(): JsonResponse
    {
        /* produce to kafka
{
	"type": string, //"sms", "email",
	"userPhone": string, // if email type passed
	"userEmail": string, // if sms type passed
	"notificationType": string //"requires_payment", "success_payment", "completed"
	"orderNum": string,
	"orderItems": [
		{
			"name": string,
			"cost": int,
			"additionalInfo": ?string
		}
		...
	],
	"deliveryType": string // "selfdelivery", "courier"
	"deliveryAddress": {
		"kladrId": ?int,
		"fullAddress": ?string,
	}
}
         */
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/OrderController.php',
        ]);
    }
}
