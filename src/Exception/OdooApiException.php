<?php

namespace Bannerstop\OdooConnect\Exception;

use Exception;

class OdooApiException extends Exception
{
    private ?string $odooMessage;

    public function __construct(
        string $message,
        int $code = 0,
        ?string $odooMessage = null,
        ?Exception $previous = null
    ) {
        $this->odooMessage = $odooMessage;
        parent::__construct($message, $code, $previous);
    }

    public function getOdooMessage(): ?string
    {
        return $this->odooMessage;
    }

    public static function fromOdooError(array $response)
    {
        $message = $response['message'] ?? 'Unknown error';
        $responseCode = $response['responseCode'] ?? 0;

        return new self(
            sprintf('Odoo API error: %s', $message),
            $responseCode,
            $message
        );
    }
}
