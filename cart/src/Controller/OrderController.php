<?php

namespace App\Controller;

use App\Entity\CartItem;
use App\Entity\Constant;
use App\Entity\Order;
use App\Entity\OrderProduct;
use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Constraints;

final class OrderController extends CommonController
{
    #[Route('/order', name: 'create', methods: ['POST'])]
    public function create(
        Request $request,
        EntityManagerInterface $entityManager
    ): Response
    {
        $payload = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $this->validate($payload, new Constraints\Collection([
            'fields' => [
                'deliveryType' => new Constraints\Required(new Constraints\Choice(['selfdelivery', 'courier'])), // todo: change to CONST
                'deliveryAddress' => new Constraints\Collection([
                    'fields' => [
                        'kladrId' => new Constraints\NotBlank(),
                        'fullAddress' => new Constraints\NotBlank(),
                    ]
                ]),
            ]
        ]));

        /** @var User $user */
        $user = $this->getUser();
        if ($user->getCartItems()->count() === 0) {
            throw new BadRequestException('Cart is empty');
        }

        $entityManager->beginTransaction();
        try {
            $orderStatus = $entityManager->getReference(Constant::class, Constant::ORDER_STATUS_PAID_ID);

            $order = new Order();
            $orderCost = 0;
            $orderProducts = new ArrayCollection();
            foreach ($user->getCartItems() as $cartItem) {
                /** @var CartItem $cartItem */
                $orderCost += $cartItem->getProduct()->getCost();

                $cartItemProduct = $cartItem->getProduct();
                $orderProduct = new OrderProduct();
                $orderProduct->setCost($cartItemProduct->getCost());
                $orderProduct->setProductId($cartItemProduct->getId());
                $orderProduct->setMeasurement($cartItemProduct->getMeasurement());
                $orderProduct->setName($cartItemProduct->getName());
                $orderProduct->setTax($cartItemProduct->getTax());
                $orderProduct->setOrder($order);
                $orderProducts->add($orderProduct);
            }

            $order->setStatus($orderStatus);
            $order->setProducts($orderProducts);
            $order->setCost($orderCost);
            $order->setUser($user);

            $user->setCartItems(new ArrayCollection());
            $user->getOrders()->add($order);

            $entityManager->persist($user);
            $entityManager->flush();
            $entityManager->commit();
        } catch (\Throwable) {
            $entityManager->rollback();
            throw new \RuntimeException('Can\'t create order');
        }


        // todo: send to kafka

        return new Response();
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
    }
}
