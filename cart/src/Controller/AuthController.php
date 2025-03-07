<?php

namespace App\Controller;

use App\Event\UserSignUpEvent;
use App\Dto\UserSignUpDto;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

final class AuthController extends CommonController
{
    /**
     * @throws ORMException
     * @throws \Exception
     */
    #[Route('/sign-up', name: 'sign-up', methods: ['POST'])]
    public function signUp(
        #[MapRequestPayload] UserSignUpDto $userSignUpDto,
        EntityManagerInterface $entityManager,
        UserRepository $userRepository,
        EventDispatcherInterface $eventDispatcher
    ): Response
    {
        $user = $userRepository->createUser($userSignUpDto);

        $entityManager->persist($user);
        $entityManager->flush();

        $eventDispatcher->dispatch(new UserSignUpEvent($user), 'user.signup');

        return new Response();
    }

    // todo: logout (tokens black list)
}
