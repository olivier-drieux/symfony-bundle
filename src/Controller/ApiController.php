<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/test', name: 'api_test')]
final class ApiController extends AbstractController
{
    public function __invoke(): JsonResponse
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        return new JsonResponse(['message' => 'API Key authentication successful']);
    }
}
