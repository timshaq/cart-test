<?php

namespace App\Controller;

use App\Entity\Constant;
use App\Entity\User;
use App\Message\Produce\UserSignUp;
use App\Dto\UserSignUp as UserSignUpDto;
use App\Repository\ConstantRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

final class AuthController extends CommonController
{

    /**
     * @throws ExceptionInterface
     * @throws ORMException
     * @throws \JsonException
     * @throws \Exception
     */
    #[Route('/sign-up', name: 'sign-up', methods: ['POST'])]
    public function signUp(
        #[MapRequestPayload] UserSignUpDto $userSignUpDto,
        Request $request,
        MessageBusInterface $messageBus,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher,
        UserRepository $userRepository,
        ConstantRepository $constantRepository
    )
    {
        $payload = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $login = $userSignUpDto->phone ?? $userSignUpDto->email;

        $userIsExist = (bool) $userRepository->findOneBy(['login' => $login]);
        if ($userIsExist) {
            throw new ConflictHttpException('There is already such a user');
        }

        // todo: add try catch
        // todo: move to repository
        $user = new User();

        $user->setPromoId($userSignUpDto->promoId);
        $user->setLogin($userSignUpDto->phone ?? $userSignUpDto->email);
        $user->setPhone($userSignUpDto->phone);
        $user->setEmail($userSignUpDto->email);
        $user->setNotificationType($entityManager->getReference(
            Constant::class,
            $userSignUpDto->notificationTypeId
        ));

        $hashedPassword = $passwordHasher->hashPassword($user, $payload['password']);
        $user->setPassword($hashedPassword);

        $entityManager->persist($user);
        $entityManager->flush();

        // todo: move to EventHandler? create message by User entity
        $notificationType = $constantRepository->find($userSignUpDto->notificationTypeId);
        $msg = new UserSignUp(
            $notificationType->getValue(),
            $userSignUpDto->phone,
            $userSignUpDto->email,
            $userSignUpDto->promoId
        );

        // todo: need only phone or email
        $messageBus->dispatch($msg);

        return new Response();
    }

    // todo: logout
}
