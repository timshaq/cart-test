<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;

class CommonController extends AbstractController
{
    private int $limit = 100;
    private int $page = 1;
    private int $offset = 0;

    public function __construct(
        protected ValidatorInterface $validator,
        protected SerializerInterface $serializer
    )
    {
    }

    protected function validate($data, $asserts): void
    {
        $errors = $this->validator->validate($data, $asserts);

        if (0 !== $errors->count()) {
            throw new BadRequestException($errors);
        }
    }

    protected function setPaginationParameters(Request $request): void
    {
        $this->limit = (int) $request->query->get('limit', 100);
        $this->page = (int) $request->query->get('page', 1);

        $this->validate($this->limit, new Assert\Positive());
        $this->validate($this->page, new Assert\Positive());

        $this->offset = $this->limit * ($this->page - 1);
    }

    protected function getLimit(): int
    {
        return $this->limit;
    }

    protected function getPage(): int
    {
        return $this->page;
    }

    protected function getOffset(): int
    {
        return $this->offset;
    }

    protected function getPaginationResponse($data): JsonResponse
    {
        return $this->json([
            'total' => count($data),
            'data' => $data,
            'limit' => $this->getLimit(),
            'page' => $this->getPage()
        ]);
    }
}
