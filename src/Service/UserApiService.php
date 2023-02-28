<?php

namespace App\Service;

use App\Dto\Response\LoginResponse;
use App\Exception\RedirectLoginException;
use App\Routes;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class UserApiService extends ApiService
{
    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     * @throws RedirectLoginException
     */
    public function login(string $route, mixed $userData): LoginResponse
    {
        return $this->request(
            'POST',
            $route,
            $userData,
            LoginResponse::class
        );
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     * @throws RedirectLoginException
     */
    public function logout(): void
    {
        $this->request(
            'GET',
            Routes::LOGOUT
        );
    }
}
