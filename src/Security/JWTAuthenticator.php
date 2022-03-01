<?php

namespace App\Security;

use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class JWTAuthenticator extends AbstractAuthenticator
{
    private $jwtEncoder;

    public function __construct(JWTEncoderInterface $jwtEncoder)
    {
        $this->jwtEncoder = $jwtEncoder;
    }

    public function supports(Request $request): ?bool
    {
        if($request->headers->has('Authorization')
        && 0 === strpos($request->headers->get('Authorization'), 'Bearer ')) {
            return true;
        }
        return false;
    }

    public function authenticate(Request $request): Passport
    {
        $jwtToken = str_replace('Bearer ', '', $request->headers->get('Authorization'));

        /**
         * JWT Token validation
         * JWTEncoderInterface can decode send to JWTUserProvider if not throw JWTFailedDecodeException
         * 
         */
        $decodedJwtToken = $this->jwtEncoder->decode($jwtToken);

        return new SelfValidatingPassport(new UserBadge($decodedJwtToken['username']));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        return new Response("Authentication Failed.", Response::HTTP_FORBIDDEN);
    }

}