<?php

namespace Bannerstop\OdooConnect\Enum;

enum Model: string
{
    case SALE_ORDER = 'sale.order';
    case SALE_ORDER_LINE = 'sale.order.line';
    case ACCOUNT_MOVE = 'account.move';
    case RES_PARTNER = 'res.partner';
}
