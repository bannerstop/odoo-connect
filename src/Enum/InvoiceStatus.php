<?php

namespace Bannerstop\OdooConnect\Enum;

enum InvoiceStatus: string
{
    case NO = 'no';
    case TO_INVOICE = 'to invoice';
    case INVOICED = 'invoiced';
}
