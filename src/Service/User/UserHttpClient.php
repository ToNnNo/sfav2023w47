<?php

namespace App\Service\User;

use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class UserHttpClient
{

    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly HttpClientInterface $httpClient
    )
    {
    }

    public function get(): array
    {
        $users = [];

        try {
            $httpResponse = $this->httpClient->request('GET', 'https://jsonplaceholder.typicode.com/users');
            $users = $httpResponse->toArray();
        } catch (TransportExceptionInterface|ClientExceptionInterface|DecodingExceptionInterface|RedirectionExceptionInterface|ServerExceptionInterface $e) {
            $this->logger->error($e->getMessage());
        }

        return $users;
    }
}
