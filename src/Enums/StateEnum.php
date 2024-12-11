<?php

namespace Bannerstop\OdooConnect\Enums;

enum StateEnum: string
{
    case QUOTE = 'draft';
    case INTERMEDIATE = 'sent';
    case SALES_ORDER = 'sale';
    case CANCEL = 'cancel';
}
