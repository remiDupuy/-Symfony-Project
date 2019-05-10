<?php
/**
 * Created by PhpStorm.
 * User: remidupuy
 * Date: 19/03/18
 * Time: 11:11
 */

namespace AppBundle\Security\Authentication;


use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class ApiUserPasswordAuthentication extends AbstractGuardAuthenticator
{

    public function __construct(EncoderFactoryInterface $encoderFactory)
    {
        $this->encoderFactory = $encoderFactory;
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new JsonResponse('The authentication is required.', Response::HTTP_UNAUTHORIZED);
    }

    public function getCredentials(Request $request)
    {
        $credentials = [];

        if(!$request->headers->get('X-USERNAME') || !$request->headers->get('X-PASSWORD')) {
            return null;
        }

        $credentials['username'] = $request->headers->get('X-USERNAME');
        $credentials['password'] = $request->headers->get('X-PASSWORD');

        return $credentials;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        return $userProvider->loadUserByUsername($credentials['username']);
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        $encoder = $this->encoderFactory->getEncoder($user);

        return $encoder->isPasswordValid($user->getPassword(), $credentials['password'], '');
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return new JsonResponse('Authentication failed.', Response::HTTP_UNAUTHORIZED);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return null;
    }

    public function supportsRememberMe()
    {
        return false;
    }
}