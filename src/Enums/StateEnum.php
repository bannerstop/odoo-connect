<?php

namespace Bannerstop\OdooConnect\Enums;

enum StateEnum: string
{
    case QUOTE = 'draft';
    case SENT = 'sent';
    case SALES_ORDER = 'sale';
    case CANCEL = 'cancel';
}
