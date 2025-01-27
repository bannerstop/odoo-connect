<?php

namespace Bannerstop\OdooConnect\Client;

use Bannerstop\OdooConnect\Exception\OdooRecordNotFoundException;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\HandlerStack;
use Psr\Http\Message\ResponseInterface;
use Bannerstop\OdooConnect\Exception\OdooClientException;
use Bannerstop\OdooConnect\Exception\OdooApiException;
use Spatie\GuzzleRateLimiterMiddleware\RateLimiterMiddleware;

class OdooClient
{
    private GuzzleClient $httpClient;
    private OdooConnection $connection;
    private int $requestsPerSecond;

    public function __construct(OdooConnection $connection, int $requestsPerSecond = 3) {
        $this->connection = $connection;
        $this->requestsPerSecond = $requestsPerSecond;

        $stack = HandlerStack::create();

        if ($this->requestsPerSecond > 0) {
            $stack->push(RateLimiterMiddleware::perSecond($this->requestsPerSecond));
        }

        $this->httpClient = new GuzzleClient([
            'handler' => $stack,
            'base_uri' => $this->connection->getBaseUrl(),
            'headers'  => [
                'api-key'       => $this->connection->getApiKey(),
                'Content-Type'  => 'application/json',
                'Accept'        => 'application/json',
            ],
        ]);
    }

    public function getConnection(): OdooConnection
    {
        return $this->connection;
    }

    public function request(string $method, string $endpoint, array $options = []): array
    {
        try {
            $response = $this->httpClient->request($method, $endpoint, $options);
            return $this->processResponse($response);
        } catch (RequestException $e) {
            throw OdooClientException::fromHttpError($e->getCode(), $e->getMessage());
        }
    }

    private function processResponse(ResponseInterface $response): array
    {
        $statusCode = $response->getStatusCode();
        $body = json_decode($response->getBody()->getContents(), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new OdooClientException('Invalid JSON response from API.');
        }

        if ($statusCode !== 200 || ($body['responseCode'] ?? null) !== 200) {
            throw OdooApiException::fromOdooError($body);
        }

        if (isset($body['success']) && $body['success'] === false) {
            $message = $body['message'] ?? '';
            if (stripos($message, 'no record found') !== false) {
                throw OdooRecordNotFoundException::fromOdooError($body);
            }
            throw OdooApiException::fromOdooError($body);
        }

        return $body['data'] ?? [];
    }
}
