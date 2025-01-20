<?php

namespace Bannerstop\OdooConnect\Exception;

use Exception;

class OdooClientException extends Exception
{
    public function __construct(string $message, int $code = 0, ?Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public static function fromHttpError(int $statusCode, string $reasonPhrase): self
    {
        return new self(
            sprintf('HTTP error %d: %s', $statusCode, $reasonPhrase),
            $statusCode
        );
    }
}
