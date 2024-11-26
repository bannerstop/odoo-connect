<?php

namespace Bannerstop\OdooConnect\Client;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;
use Bannerstop\OdooConnect\Exceptions\ClientException;
use Bannerstop\OdooConnect\Exceptions\OdooException;

class OdooClient
{
    private GuzzleClient $httpClient;

    public function __construct(
        private readonly OdooConnection $connection
    ) {
        $this->httpClient = new GuzzleClient([
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
            throw new ClientException("HTTP error: {$e->getMessage()}", $e->getCode(), $e);
        }
    }

    private function processResponse(ResponseInterface $response): array
    {
        $statusCode = $response->getStatusCode();
        $body = json_decode($response->getBody()->getContents(), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new ClientException('Invalid JSON response from API.');
        }

        if ($statusCode !== 200 || ($body['responseCode'] ?? null) !== 200) {
            $message = $body['message'] ?? 'Unknown error';
            $code = $body['responseCode'] ?? $statusCode;
            throw new OdooException($message, $code);
        }

        return $body['data'] ?? [];
    }
}
