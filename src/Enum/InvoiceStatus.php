<?php

namespace Bannerstop\OdooConnect\Enum;

enum InvoiceStatus: string
{
    case NO = 'no';
    case TO_INVOICE = 'to invoice';
    case INVOICED = 'invoiced';
    case UPSELLING = 'upselling';
    case UNKNOWN = 'unknown';

    public static function safeFrom(string $value): self
    {
        return self::tryFrom($value) ?? self::UNKNOWN;
    }
}
