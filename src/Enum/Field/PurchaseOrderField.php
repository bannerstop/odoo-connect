<?php

namespace Bannerstop\OdooConnect\Enum\Field;

enum PurchaseOrderField: string
{
    case ID = 'id';
    case NAME = 'name';
    case STATE = 'state';
    case PARTNER_ID = 'partner_id';
    case PARTNER_REF = 'partner_ref';
    case AMOUNT_TOTAL = 'amount_total';
    case AMOUNT_UNTAXED = 'amount_untaxed';
    case AMOUNT_TAX = 'amount_tax';
    case ORDER_LINE = 'order_line';
    case DATE_ORDER = 'date_order';
    case DATE_APPROVE = 'date_approve';
    case CREATE_DATE = 'create_date';
    case DATE_PLANNED = 'date_planned';
    case NOTES = 'notes';
    case INVOICE_STATUS = 'invoice_status';
}
