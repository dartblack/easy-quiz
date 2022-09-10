<?php

namespace App\Services;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class QuestionLoadService
{
    const API_BASE_URL = 'https://opentdb.com';
    const MULTIPLE_TYPE = 'multiple';
    const BOOLEAN_TYPE = 'boolean';
    private HttpClientInterface $client;

    public function __construct()
    {
        $this->client = HttpClient::createForBaseUri(self::API_BASE_URL);
    }

    /**
     * @param string $type
     * @param int $amount
     * @return array
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function loadQuestions(string $type, int $amount = 10): array
    {
        $response = $this->client->request(Request::METHOD_GET, '/api.php', [
            'query' => [
                'amount' => $amount,
                'type' => $type,
                'difficulty' => 'easy'
            ],
        ]);
        if ($response->getStatusCode() == Response::HTTP_OK) {
            return $response->toArray();
        }
        return ['response_code', 'results' => []];
    }
}