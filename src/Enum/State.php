<?php

namespace Bannerstop\OdooConnect\Enum;

enum State: string
{
    case QUOTE = 'draft';
    case INTERMEDIATE = 'sent';
    case SALES_ORDER = 'sale';
    case CANCEL = 'cancel';
}
