<?php

namespace App\Service;

use App\Dto\Response\PostResponse;
use App\Exception\RedirectLoginException;
use App\Routes;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class PostApiService extends ApiService
{
    /**
     * @return PostResponse[]
     *
     * @throws RedirectLoginException
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getAll(): array
    {
        return $this->request(
            'GET',
            Routes::API_POST,
            null,
            PostResponse::class
        );
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     * @throws RedirectLoginException
     */
    public function get(int $id): PostResponse
    {
        return $this->request(
            'GET',
            Routes::API_POST.'/'.$id,
            null,
            PostResponse::class
        );
    }
}
