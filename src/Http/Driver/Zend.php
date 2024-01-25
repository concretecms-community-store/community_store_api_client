<?php

declare(strict_types=1);

namespace CommunityStore\APIClient\Http\Driver;

use Zend\Http\Client;
use Zend\Http\Request;
use CommunityStore\APIClient\Http\Driver;

/**
 * @internal
 */
final class Zend implements Driver
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
        $request = new Request();
        $request
            ->setMethod($method)
            ->setUri($url)
        ;
        if ($body !== null) {
            $request->setContent($body);
        }
        $requestHeaders = $request->getHeaders();
        foreach ($headers as $name => $value) {
            $requestHeaders->addHeaderLine($name, $value);
        }
        $response = $this->client->send($request);

        return [
            $response->getStatusCode(),
            $response->getReasonPhrase(),
            $response->getBody(),
            $response->getHeaders()->toArray(),
        ];
    }
}
