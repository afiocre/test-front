<?php

namespace App\Service;

use App\Exception\RedirectLoginException;
use App\Security\User;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ApiService
{
    public function __construct(
        private readonly HttpClientInterface $backendClient,
        private readonly SerializerInterface $serializer,
        private readonly Security $security,
        protected RequestStack $requestStack
    ) {
    }

    /**
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectLoginException
     */
    protected function request(
        string $method,
        string $url,
        mixed $data = null,
        string $dataClass = null
    ): mixed {
        // Request
        $options = ['json' => $data];
        /** @var ?User $user */
        $user = $this->security->getUser();
        if ($user) {
            $options['headers'] = ['Authorization' => 'Bearer '.$user->getToken()];
        }

        // Call API
        $response = $this->backendClient->request($method, $url, $options);

        // Response
        if (Response::HTTP_UNAUTHORIZED === $response->getStatusCode()
            || Response::HTTP_FORBIDDEN === $response->getStatusCode()
        ) {
            throw new RedirectLoginException($url, $response->getStatusCode());
        }
        $content = $response->getContent();
        if ($dataClass) {
            $contentDecode = json_decode($content);
            if (is_array($contentDecode)) {
                $items = [];
                foreach ($contentDecode as $item) {
                    $items[] = $this->serializer->deserialize(json_encode($item), $dataClass, JsonEncoder::FORMAT);
                }

                return $items;
            } else {
                return $this->serializer->deserialize($content, $dataClass, JsonEncoder::FORMAT);
            }
        }

        if ($this->isJson($content)) {
            return json_decode($content, true);
        }

        return $content;
    }

    private function isJson(string $content): bool
    {
        json_decode($content);

        return JSON_ERROR_NONE == json_last_error();
    }
}
