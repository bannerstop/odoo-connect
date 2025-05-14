<?php

namespace Bannerstop\OdooConnect\Enum;

enum State: string
{
    case QUOTE = 'draft';
    case INTERMEDIATE = 'sent';
    case SALES_ORDER = 'sale';
    case CANCEL = 'cancel';
    case UNKNOWN = 'unknown';

    public static function safeFrom(string $value): self
    {
        return self::tryFrom($value) ?? self::UNKNOWN;
    }
}
