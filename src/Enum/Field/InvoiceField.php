<?php

namespace Bannerstop\OdooConnect\Enum\Field;

enum InvoiceField: string
{
    case ID = 'name';
    case AMOUNT_TOTAL = 'amount_total';
    case AMOUNT_UNTAXED = 'amount_untaxed';
    case AMOUNT_RESIDUAL = 'amount_residual';
    case CREATE_DATE = 'create_date';
}
