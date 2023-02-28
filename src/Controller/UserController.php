<?php

namespace App\Controller;

use App\Routes;
use App\Security\User;
use App\Service\UserApiService;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

#[Route('/user', name: 'user_')]
class UserController extends AbstractController
{
    public function __construct(
        private readonly UserApiService $userApi,
        private readonly TokenStorageInterface $tokenStorage,
    ) {
    }

    #[Route('/login', name: 'login')]
    public function login(): Response
    {
        return $this->render('user/login.html.twig');
    }

    #[Route('/login/google', name: 'login_google')]
    public function loginGoogle(ClientRegistry $clientRegistry): RedirectResponse
    {
        return $clientRegistry->getClient('google_main')->redirect(['email', 'profile'], []);
    }

    #[Route('/login/google-check', name: 'login_google_check')]
    public function loginGoogleCheck(Request $request, ClientRegistry $clientRegistry): RedirectResponse
    {
        return $this->loginCheck(
            Routes::LOGIN_GOOGLE,
            $request,
            $clientRegistry->getClient('google_main')->getAccessToken()
        );
    }

    #[Route('/logout', name: 'logout')]
    public function logout(Request $request): Response
    {
        $this->userApi->logout();
        $session = $request->getSession();
        $session->remove('_security_main');
        $this->tokenStorage->setToken(null);

        return $this->redirectToRoute('post_list');
    }

    private function loginCheck(string $route, Request $request, mixed $userData): RedirectResponse
    {
        $loginResponse = $this->userApi->login($route, $userData);
        $user = User::createFromLoginResponse($loginResponse);
        $token = new UsernamePasswordToken($user, 'main', $user->getRoles());
        $this->tokenStorage->setToken($token);
        $session = $request->getSession();
        $session->set('_security_main', serialize($token));

        return $this->redirectToRoute('post_list');
    }
}
