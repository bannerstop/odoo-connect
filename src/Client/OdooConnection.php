<?php

namespace Bannerstop\OdooConnect\Client;

use InvalidArgumentException;

class OdooConnection
{
    public function __construct(
        public readonly string $baseUrl,
        public readonly string $apiKey,
        public readonly string $db
    ) {
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
