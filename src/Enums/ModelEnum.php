<?php

namespace Bannerstop\OdooConnect\Enums;

enum ModelEnum: string
{
    case SALE_ORDER = 'sale.order';
    case SALE_ORDER_LINE = 'sale.order.line';
    case ACCOUNT_MOVE = 'account.move';
}
