<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ExceptionSubscriber implements EventSubscriberInterface
{
    private RouterInterface $router;
    private SessionInterface $session;
    private FlashBagInterface $flashBag;
    private TokenStorageInterface $tokenStorage;

    public function __construct(RouterInterface $router, RequestStack $requestStack, TokenStorageInterface $tokenStorage)
    {
        $this->router = $router;
        $this->session = $requestStack->getSession();
        /** @var Session $session */
        $session = $requestStack->getSession();
        $this->flashBag = $session->getFlashBag();
        $this->tokenStorage = $tokenStorage;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'RedirectLoginException',
        ];
    }

    public function RedirectLoginException(ExceptionEvent $event): void
    {
        $message = null;
        $code = $event->getThrowable()->getCode();
        switch ($code) {
            case Response::HTTP_UNAUTHORIZED:
                $message = 'Vous êtes déconnecté';
                $this->session->remove('_security_main');
                $this->tokenStorage->setToken(null);
                break;
            case Response::HTTP_FORBIDDEN:
                $message = 'Page non autorisée';
                break;
        }
        if ($message) {
            $this->flashBag->add('danger', $message);
            $response = new RedirectResponse($this->router->generate('user_login'));
            $event->setResponse($response);
        }
    }
}
