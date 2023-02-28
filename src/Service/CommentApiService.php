<?php

namespace App\Service;

use App\Dto\Request\CommentRequest;
use App\Exception\RedirectLoginException;
use App\Routes;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class CommentApiService extends ApiService
{
    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     * @throws RedirectLoginException
     */
    public function create(CommentRequest $commentRequest): void
    {
        $this->request(
            'POST',
            Routes::API_COMMENT,
            $commentRequest
        );
    }
}
