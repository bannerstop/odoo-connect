<?php

namespace Bannerstop\OdooConnect\Enum;

enum PurchaseState: string
{
    case DRAFT = 'draft';
    case SENT = 'sent';
    case PURCHASE = 'purchase';
    case CANCEL = 'cancel';
    case UNKNOWN = 'unknown';

    public static function safeFrom(string $value): self
    {
        return self::tryFrom($value) ?? self::UNKNOWN;
    }
}
