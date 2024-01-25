<?php

declare(strict_types=1);

namespace CommunityStore\APIClient;

use CommunityStore\APIClient\Entity\Configuration;
use CommunityStore\APIClient\Entity\FulfilmentStatus;
use CommunityStore\APIClient\Entity\Order;
use CommunityStore\APIClient\Entity\Pagination;
use CommunityStore\APIClient\Exception\ResponseException;
use CommunityStore\APIClient\Query\OrderPatch;
use CommunityStore\APIClient\Query\Orders;
use DateTimeImmutable;
use InvalidArgumentException;
use RuntimeException;
use Throwable;

class Client
{
    private string $baseURL;

    private string $clientID;

    private string $clientSecret;

    private array $scopes;

    private Http\Driver $httpClient;

    private Cache\Driver $cache;

    private string $cachePrefix;

    /**
     * @param string[] $scopes
     * @param \GuzzleHttp\Client|\Zend\Http\Client $httpClient
     * @param \Psr\Cache\CacheItemPoolInterface|\Concrete\Core\Cache\Cache|null $cache
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(string $baseURL, string $clientID, string $clientSecret, array $scopes, $httpClient, $cache = null)
    {
        $this->baseURL = rtrim($baseURL, '/');
        $this->clientID = $clientID;
        $this->clientSecret = $clientSecret;
        $this->scopes = Scope::validate($scopes);
        $this->httpClient = $this->getHttpDriver($httpClient);
        $this->cache = $this->getCacheDriver($cache);
        $this->cachePrefix = 'cs.api.client.' . sha1("{$this->baseURL}:{$this->clientID}:" . implode('+', $this->scopes)) . '.';
    }

    /**
     * @see \CommunityStore\APIClient\Scope::CONFIG_READ
     * @since community_store_api v1.0.4
     */
    public function getConfiguration(): Configuration
    {
        $response = $this->getJSON("{$this->baseURL}/cs/api/v1/config");

        return new Configuration($response['data']);
    }

    /**
     * @see \CommunityStore\APIClient\Scope::ORDERS_READ
     *
     * @return \CommunityStore\APIClient\Entity\FulfilmentStatus[]
     */
    public function getFulfilmentStatuses(): array
    {
        $response = $this->getJSON("{$this->baseURL}/cs/api/v1/fulfilmentstatuses");
        $result = [];
        foreach ($response['data'] as $item) {
            $result[] = new FulfilmentStatus($item);
        }

        return $result;
    }

    /**
     * @see \CommunityStore\APIClient\Scope::ORDERS_READ
     */
    public function getOrder(int $id): ?Order
    {
        if ($id <= 0) {
            return null;
        }
        try {
            $response = $this->getJSON("{$this->baseURL}/cs/api/v1/orders/{$id}");
        } catch (ResponseException $x) {
            if ($x->getCode() === 404) {
                return null;
            }
        }

        return new Order($response['data']);
    }

    /**
     * @see \CommunityStore\APIClient\Scope::ORDERS_READ
     *
     * @return \CommunityStore\APIClient\Entity\Order[]
     */
    public function getOrders(?Orders $query = null, Pagination &$pagination = null): array
    {
        return $this->getOrdersFromURL("{$this->baseURL}/cs/api/v1/orders{$query}", $pagination);
    }

    /**
     * @return \CommunityStore\APIClient\Entity\Order[]
     */
    public function getNextOrders(Pagination $pagination, Pagination &$newPagination = null): array
    {
        return $this->getOrdersFromURL($pagination->getLink('next'), $newPagination);
    }

    /**
     * @return \CommunityStore\APIClient\Entity\Order[]
     */
    public function getPreviousOrders(Pagination $pagination, Pagination &$newPagination = null): array
    {
        return $this->getOrdersFromURL($pagination->getLink('previous'), $newPagination);
    }

    /**
     * @see \CommunityStore\APIClient\Scope::ORDERS_WRITE
     */
    public function updateOrder(OrderPatch $patch): Order
    {
        $body = json_encode(['data' => $patch->getFields()], JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR);

        $response = $this->patchJSON("{$this->baseURL}/cs/api/v1/orders/{$patch->id}", $body);

        return new Order($response['data']);
    }

    /**
     * @throws \InvalidArgumentException
     */
    private function getHttpDriver($httpClient): Http\Driver
    {
        if ($httpClient instanceof \GuzzleHttp\Client) {
            return new Http\Driver\Guzzle($httpClient);
        }
        if ($httpClient instanceof \Zend\Http\Client) {
            return new Http\Driver\Zend($httpClient);
        }
        throw new InvalidArgumentException(sprintf(
            '$httpClient must be an instance of %s or %s, %s provided',
            \GuzzleHttp\Client::class,
            \Zend\Http\Client::class,
            is_object($httpClient) ? get_class($httpClient) : gettype($httpClient)
        ));
    }

    /**
     * @throws \InvalidArgumentException
     */
    private function getCacheDriver($cache): Cache\Driver
    {
        if ($cache === null) {
            return Cache\Driver\SharedMemory::getInstance();
        }
        if ($cache instanceof \Concrete\Core\Cache\Cache) {
            return new Cache\Driver\Concrete($cache);
        }
        if ($cache instanceof \Psr\Cache\CacheItemPoolInterface) {
            return new Cache\Driver\Psr($cache);
        }
        throw new InvalidArgumentException(sprintf(
            '$$cache must be null or an instance of %s or %s, %s provided',
            \Concrete\Core\Cache\Cache::class,
            \Psr\Cache\CacheItemPoolInterface::class,
            is_object($cache) ? get_class($cache) : gettype($cache)
        ));
    }

    private function getAccessToken(): string
    {
        $accessToken = $this->cache->getString($this->cachePrefix . 'access-token') ?? '';
        if ($accessToken === '') {
            [$accessToken, $expiresAt] = $this->createAccessToken();
            $this->cache->setString($this->cachePrefix . 'access-token', $accessToken, $expiresAt);
        }

        return $accessToken;
    }

    /**
     * @throws \RuntimeException
     */
    private function createAccessToken(): array
    {
        if ($this->baseURL === '') {
            throw new RuntimeException('The base URL of the Store Client is missing.');
        }
        if (!filter_var($this->baseURL, FILTER_VALIDATE_URL)) {
            throw new RuntimeException('The base URL of the Store Client is malformed.');
        }
        if ($this->clientID === '') {
            throw new RuntimeException('The client ID of the Store Client is missing.');
        }
        if ($this->clientSecret === '') {
            throw new RuntimeException('The client secret of the Store Client is missing.');
        }
        $now = time();
        [$statusCode, $reasonPhrase, $responseBody] = $this->httpClient->send(
            'POST',
            "{$this->baseURL}/oauth/2.0/token",
            [
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
            http_build_query([
                'grant_type' => 'client_credentials',
                'scope' => implode(' ', $this->scopes),
                'client_id' => $this->clientID,
                'client_secret' => $this->clientSecret,
            ])
        );
        if ($statusCode < 200 || $statusCode > 299) {
            throw new RuntimeException("Failed to retrieve the Store access token: {$reasonPhrase}", $statusCode);
        }
        $responseData = json_decode($responseBody, true, JSON_THROW_ON_ERROR);
        if (!is_array($responseData)) {
            throw new RuntimeException('Failed to retrieve the Store access token: invalid response body');
        }
        if (empty($responseData['access_token']) || !is_string($responseData['access_token'])) {
            throw new RuntimeException('Missing access_token in Store access token response');
        }
        $expiresIn = empty($responseData['expires_in']) || !is_numeric($responseData['expires_in']) ? -1 : (int) $responseData['expires_in'];
        if ($expiresIn < 1) {
            throw new RuntimeException('Missing or invalid expires_in in Store access token response');
        }

        return [
            $responseData['access_token'],
            (new DateTimeImmutable())->setTimestamp($now + $expiresIn),
        ];
    }

    private function getJSON(string $url, array &$responseHeaders = []): array
    {
        return $this->sendAndExpectJSON('GET', $url, null, $responseHeaders);
    }

    private function patchJSON(string $url, string $body, array &$responseHeaders = []): array
    {
        return $this->sendAndExpectJSON('PATCH', $url, $body, $responseHeaders);
    }

    private function sendAndExpectJSON(string $method, string $url, ?string $body = null, array &$responseHeaders = []): array
    {
        $accessToken = $this->getAccessToken();
        [$statusCode, $reasonPhrase, $responseBody, $responseHeaders] = $this->httpClient->send($method, $url, ['Authorization' => "Bearer {$accessToken}"], $body);
        if ($statusCode < 200 || $statusCode > 299) {
            $message = '';
            try {
                $responseData = json_decode($responseBody, true, JSON_THROW_ON_ERROR);
                if (is_array($responseData)) {
                    if (($responseData['error'] ?? null) === true && is_string($responseData['errors'][0] ?? null)) {
                        $message = trim($responseData['errors'][0]);
                    }
                }
            } catch (Throwable $_) {
            }
            if ($message === '') {
                $message = "Failed to fetch data from Store at {$url}: {$reasonPhrase}";
            }
            throw new ResponseException($message, $statusCode);
        }
        $responseData = json_decode($responseBody, true, JSON_THROW_ON_ERROR);

        return $responseData;
    }

    /**
     * @return \CommunityStore\APIClient\Entity\Order[]
     */
    private function getOrdersFromURL(string $url, Pagination &$newPagination = null): array
    {
        if ($url === '') {
            $newPagination = null;
            return [];
        }
        $response = $this->getJSON($url);
        $newPagination = new Pagination($response['meta']['pagination'] ?? []);
        $result = [];
        foreach ($response['data'] as $item) {
            $result[] = new Order($item);
        }

        return $result;
    }
}
