<?php

namespace App\Controller;

use App\Dto\UserSignUpDto;
use App\Repository\UserRepository;
use App\Service\KafkaProduceService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

final class AuthController extends AbstractController
{
    #[Route('/sign-up', name: 'sign-up', methods: ['POST'])]
    public function signUp(
        #[MapRequestPayload] UserSignUpDto $userSignUpDto,
        EntityManagerInterface $entityManager,
        UserRepository $userRepository,
        KafkaProduceService $kafkaProduceService
    ): Response
    {
        $entityManager->beginTransaction();
        try {
            $user = $userRepository->createUser($userSignUpDto);

            $entityManager->persist($user);
            $entityManager->flush();

//            $kafkaProduceService->sendNewUser($user);

            $entityManager->commit();
        } catch (\Throwable) {
            $entityManager->rollback();
            throw new \RuntimeException('Can\'t create user');
        }

        return new Response();
    }
}
