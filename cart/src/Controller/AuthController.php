<?php

namespace App\Controller;

use App\Entity\Constant;
use App\Entity\User;
use App\Message\Produce\UserSignUp;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Constraints;

final class AuthController extends CommonController
{
    /**
     * @throws \JsonException
     * @throws ExceptionInterface
     * @throws \Exception
     */
    #[Route('/sign-up', name: 'sign-up', methods: ['POST'])]
    public function signUp(
        Request $request,
        MessageBusInterface $messageBus,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher,
        UserRepository $userRepository
    )
    {
        $payload = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $this->validate($payload, new Constraints\Collection([
            'fields' => [
                'notificationTypeId' => new Constraints\Required(new Constraints\Choice([
                    Constant::NOTIFICATION_TYPE_SMS_ID,
                    Constant::NOTIFICATION_TYPE_EMAIL_ID,
                ])), // todo: change to CONST
                'password' => new Constraints\Required([
                    new Constraints\Length(['min' => 6, 'max' => 32]),
                    new Constraints\NotBlank()
                ]),
                'promoId' => new Constraints\Optional(new Constraints\Type('string')),
            ],
            'allowExtraFields' => true
        ]));

        // todo: change logic
        $contactAssert = ['allowExtraFields' => true];
        if ($payload['notificationTypeId'] === Constant::NOTIFICATION_TYPE_SMS_ID) {
            $contactAssert['fields']['userPhone'] = new Constraints\Required([
                new Constraints\Regex('/^\d{10}$/'),
            ]);
        }
        if ($payload['notificationTypeId'] === Constant::NOTIFICATION_TYPE_EMAIL_ID) {
            $contactAssert['fields']['userEmail'] = new Constraints\Required([
                new Constraints\Email()
            ]);
        }
        $this->validate($payload, new Constraints\Collection($contactAssert));

        $login = $payload['userPhone'] ?? $payload['userEmail'];

        $userIsExist = (bool) $userRepository->findOneBy(['login' => $login]);
        if ($userIsExist) {
            throw new ConflictHttpException('There is already such a user');
        }

        $user = new User();

        $user->setPromoId($payload['promoId']);
        $user->setLogin($login);
        $user->setPhone($payload['userPhone'] ?? null);
        $user->setEmail($payload['userEmail'] ?? null);

        $hashedPassword = $passwordHasher->hashPassword($user, $payload['password']);
        $user->setPassword($hashedPassword);

        $entityManager->persist($user);
        $entityManager->flush();

        $msg = new UserSignUp(
            $payload['type'],
            $payload['userPhone'] ?? null,
            $payload['userEmail'] ?? null,
            $payload['promoId']
        );

        // todo: need only phone or email
        // todo: why send messageKey and etc props in body (value)
        $messageBus->dispatch($msg);

        return new Response();
    }

    // todo: logout
}
