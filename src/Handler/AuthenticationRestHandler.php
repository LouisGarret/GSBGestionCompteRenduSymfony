<?php
namespace App\Handler;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

class AuthenticationRestHandler implements AuthenticationSuccessHandlerInterface, AuthenticationFailureHandlerInterface {

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception) {
        return new Response('', Response::HTTP_UNAUTHORIZED);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token) {
        return new Response('', Response::HTTP_NO_CONTENT);
    }
}