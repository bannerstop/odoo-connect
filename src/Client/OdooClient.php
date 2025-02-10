<?php

namespace Bannerstop\OdooConnect\Client;

use Bannerstop\OdooConnect\Exception\OdooRecordNotFoundException;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\TransferException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use Psr\Http\Message\ResponseInterface;
use Bannerstop\OdooConnect\Exception\OdooClientException;
use Bannerstop\OdooConnect\Exception\OdooApiException;
use Spatie\GuzzleRateLimiterMiddleware\RateLimiterMiddleware;

class OdooClient
{
    private GuzzleClient $httpClient;

    public function __construct(
        private readonly OdooConnection $connection,
        private readonly int $requestsPerSecond = 3,
        private readonly int $maxRetries = 3,
        private readonly float $timeout = 10.0
    ) {
        $stack = HandlerStack::create();

        if ($this->requestsPerSecond > 0) {
            $stack->push(RateLimiterMiddleware::perSecond($this->requestsPerSecond));
        }

        $stack->push($this->createRetryMiddleware());

        $this->httpClient = new GuzzleClient([
            'handler' => $stack,
            'base_uri' => $this->connection->getBaseUrl(),
            'headers'  => [
                'api-key'       => $this->connection->getApiKey(),
                'Content-Type'  => 'application/json',
                'Accept'        => 'application/json',
            ],
            'timeout' => $this->timeout,
            'connect_timeout' => $this->timeout
        ]);
    }
    
    private function createRetryMiddleware(): callable
    {
        return Middleware::retry(
            decider: function ($retries, $request, $response = null, $exception = null) {
                $isTransferException = $exception instanceof TransferException;
                $shouldRetry = $retries < $this->maxRetries && $isTransferException;
    
                if (!$shouldRetry && $isTransferException) {
                    throw new OdooClientException(
                        sprintf('Max retries (%d) reached with error: %s', $this->maxRetries, $exception->getMessage())
                    );
                }
    
                return $shouldRetry;
            },
            delay: fn($retries) => 1000 * 2 ** ($retries - 1)
        );
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
