<?php

namespace App\Controller;

use App\Message\UserSignUp;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Constraints;

final class UserController extends CommonController
{
    /**
     * @throws \JsonException
     * @throws ExceptionInterface
     * @throws \Exception
     */
    #[Route('/sign-up', name: 'sign-up', methods: ['POST'])]
    public function signUp(
        Request                    $request,
        MessageBusInterface        $messageBus
    )
    {
        $payload = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $this->validate($payload, new Constraints\Collection([
            'fields' => [
                'type' => new Constraints\Required(new Constraints\Choice(['sms', 'email'])), // todo: change to CONST
                'promoId' => new Constraints\Optional(new Constraints\Type('string')),
            ],
            'allowExtraFields' => true
        ]));

        $contactAssert = ['allowExtraFields' => true];
        if ($payload['type'] === 'sms') {
            $contactAssert['fields']['userPhone'] = new Constraints\Required([
                new Constraints\Regex('/^\d{10}$/'),
            ]);
        } else {
            $contactAssert['fields']['userEmail'] = new Constraints\Required([
                new Constraints\Email()
            ]);
        }
        $this->validate($payload, new Constraints\Collection($contactAssert));

        $msg = new UserSignUp(
            $payload['type'],
            $payload['type'] === 'sms' ? $payload['userPhone'] : null,
            $payload['type'] === 'email' ? $payload['userEmail'] : null,
            $payload['promoId']
        );

        // todo: need only phone or email
        // todo: why send messageKey and etc props in body (value)
        $messageBus->dispatch($msg);

        return new Response();
    }
}
