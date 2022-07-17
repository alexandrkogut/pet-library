<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ApiController extends AbstractController
{
    protected function getErrorResponse(ConstraintViolationListInterface $violationList): JsonResponse
    {
        $response = [];

        /** @var ConstraintViolation $violation */
        foreach ($violationList as $violation) {
            $response[] = [
                'field' => $violation->getPropertyPath(),
                'message' => $violation->getMessage(),
            ];
        }

        return $this->json($response, Response::HTTP_BAD_REQUEST);
    }

}