<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class SecurityController extends AbstractController
{
    #[Route('/auth/login', name: 'auth_login', methods: ['POST'])]
    public function login(): JsonResponse
    {
        // L'authentification est gérée par LoginAuthenticator, cette méthode ne sera jamais atteinte
        // si l'authentification échoue (une réponse sera renvoyée avant).
        throw new \LogicException('This should not be reached!');
    }
}
