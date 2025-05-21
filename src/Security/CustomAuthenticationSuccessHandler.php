<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

class CustomAuthenticationSuccessHandler implements AuthenticationSuccessHandlerInterface 
{
    private RouterInterface $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token): Response|null
    {
        $user = $token->getUser();
        $roles = $user->getRoles();

        if (in_array('ROLE_LIBRARIAN', $roles)) {
            return new RedirectResponse($this->router->generate('app_dashboard'));
        }

        if (in_array('ROLE_USER', $roles)) {
            return new RedirectResponse($this->router->generate('app_homepage'));
        }
        return new RedirectResponse($this->router->generate('app_login'));
    }
}