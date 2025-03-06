<?php

namespace App\Controller;

use App\Message\Produce\UserSignUp;
use App\Dto\UserSignUpDto;
use App\Repository\ConstantRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;

final class AuthController extends CommonController
{
    /**
     * @throws ExceptionInterface
     * @throws ORMException
     * @throws \Exception
     */
    #[Route('/sign-up', name: 'sign-up', methods: ['POST'])]
    public function signUp(
        #[MapRequestPayload] UserSignUpDto $userSignUpDto,
        MessageBusInterface $messageBus,
        EntityManagerInterface $entityManager,
        UserRepository $userRepository,
        ConstantRepository $constantRepository
    ): Response
    {
        $user = $userRepository->createUser($userSignUpDto);

        $entityManager->persist($user);
        $entityManager->flush();

        // todo: move to EventHandler? create message by User entity
        $notificationType = $constantRepository->find($userSignUpDto->getNotificationTypeId());
        $msg = new UserSignUp(
            $notificationType->getValue(),
            $userSignUpDto->getPhone(),
            $userSignUpDto->getEmail(),
            $userSignUpDto->getPromoId()
        );

        // todo: need only phone or email
        $messageBus->dispatch($msg);

        return new Response();
    }

    // todo: logout (tokens black list)
}
