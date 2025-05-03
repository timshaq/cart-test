<?php

namespace App\Service;

use DateTime;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use stdClass;
use Symfony\Component\HttpKernel\KernelInterface;

readonly class ReportService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private KernelInterface        $kernel
    )
    {

    }

    /**
     * @param string $id
     * @return string
     * @throws Exception
     * @throws \DateMalformedStringException
     */
    public function generateCompletedOrdersReport(string $id): string
    {
        $from = (new DateTime())->modify('-1 day')->setTime(0, 0);
        $to = (new DateTime())->modify('-1 day')->setTime(23, 59, 59, 999999);

        $qb = $this->entityManager->getConnection()->createQueryBuilder();
        $qb->select([
            '_order.user_id as user_id',
            'order_product.cost as product_cost',
            'product.name as product_name'
        ])
            ->from('`order`', '_order')
            ->join('_order', 'order_product', 'order_product', '_order.id = order_product.order_id')
            ->join('order_product', 'product', 'product', 'order_product.product_id = product.id')
            ->where($qb->expr()->gte('_order.date', $qb->expr()->literal($from->format('Y-m-d'))))
            ->andWhere($qb->expr()->lte('_order.date', $qb->expr()->literal($to->format('Y-m-d'))))
        ;

        $orders = $qb->fetchAllAssociative();

        $filePath = sprintf(
            $this->kernel->getProjectDir() . '/reports/%s.jsonl',
            $id
        );

        $file = new \SplFileObject($filePath, 'w');

        foreach ($orders as $order) {
            $user = new stdClass();
            $user->id = $order['user_id'];
            $jsonString = [
                'product_name' => $order['product_name'],
                'price' => $order['product_cost'],
                'amount' => 1,
                'user' => $user,
            ];
            $file->fwrite(json_encode($jsonString, JSON_UNESCAPED_UNICODE) . PHP_EOL);
        }

        return $id;
    }
}
