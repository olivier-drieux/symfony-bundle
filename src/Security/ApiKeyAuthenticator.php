<?php

namespace App\Security;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

/**
 * Class ApiKeyAuthenticator.
 */
class ApiKeyAuthenticator extends AbstractAuthenticator
{
    // Const define length of API key
    final public const API_KEY_LENGTH = 64;

    public function __construct(
        private UserRepository $userRepository,
    ) {
    }
    
    /**
     * Called on every request to decide if this authenticator should be
     * used for the request. Returning `false` will cause this authenticator
     * to be skipped.
     *
     * @param Request $request
     *
     * @return bool|null
     */
    public function supports(Request $request): bool|null
    {
        return $request->headers->has('X-Api-Key');
    }

    /**
     * @param Request $request
     *
     * @return Passport
     */
    public function authenticate(Request $request): Passport
    {
        // Retrieve API key from request
        $apiKey = $request->headers->get('X-Api-Key');

        if (null === $apiKey || '' === $apiKey) {
            /*
             * The token header was empty, authentication fails with HTTP Status
             * Code 401 Unauthorized
             */
            throw new CustomUserMessageAuthenticationException('api-engine.security.api_key.required');
        }

        return new SelfValidatingPassport(new UserBadge($apiKey, function (string $apiKey): ?UserInterface {
            return $this->userRepository->findOneBy(['apiKey' => $apiKey]);
        }));
    }

    /**
     * You might need to overwrite this method as you need.
     *
     * @param Request        $request
     * @param TokenInterface $token
     * @param string         $firewallName
     *
     * @return Response|null
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): Response|null
    {
        // Return null to let the request continue
        return null;
    }

    /**
     * @param Request                      $request
     * @param AuthenticationException|null $exception default set to null
     *
     * @return Response|null
     *
     * @throws ApiException
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException|null $exception = null): Response|null
    {
        $data = [
            // you may want to customize or obfuscate the message first
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData()),
            'e' => 'E',
            // or to translate this message
            // $this->translator->trans($exception->getMessageKey(), $exception->getMessageData())
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }
}
