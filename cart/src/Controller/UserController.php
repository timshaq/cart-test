<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class UserController extends AbstractController
{
    #[Route('/registration', name: 'registration', methods: ['POST'])]
    public function registration(Request $request): JsonResponse
    {
        /* send to kafka
{
	"type": "string", //"sms", "email",
	"userPhone": string, // if email type passed
	"userEmail": string, // if sms type passed
	"promoId": ?string //uuid4
}
         */
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/UserController.php',
        ]);
    }
}
