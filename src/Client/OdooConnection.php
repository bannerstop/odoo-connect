<?php

namespace Bannerstop\OdooConnect\Client;

use InvalidArgumentException;

class OdooConnection
{
    private string $baseUrl;
    private string $apiKey;
    private string $db;

    public function __construct(string $baseUrl, string $apiKey, string $db)
    {
        $this->baseUrl = $baseUrl;
        $this->apiKey = $apiKey;
        $this->db = $db;
        $this->validateUrl($baseUrl);
    }

    private function validateUrl(string $url): void
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new InvalidArgumentException("Invalid base URL: $url");
        }
    }

    public function getBaseUrl(): string
    {
        return rtrim($this->baseUrl, '/');
    }

    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    public function getDb(): string
    {
        return $this->db;
    }
}
