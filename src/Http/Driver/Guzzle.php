<?php

declare(strict_types=1);

namespace CommunityStore\APIClient\Http\Driver;

use CommunityStore\APIClient\Http\Driver;
use GuzzleHttp\Client;

/**
 * @internal
 */
final class Guzzle implements Driver
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * {@inheritdoc}
     *
     * @see \CommunityStore\APIClient\Http\Driver::send()
     */
    public function send(string $method, string $url, array $headers = [], ?string $body = null): array
    {
        $options = [
            'http_errors' => false,
        ];
        if ($headers !== []) {
            $options['headers'] = $headers;
        }
        if ($body !== null) {
            $options['body'] = $body;
        }
        $response = $this->client->request($method, $url, $options);

        return [
            $response->getStatusCode(),
            $response->getReasonPhrase(),
            $response->getBody()->getContents(),
            $response->getHeaders(),
        ];
    }
}
