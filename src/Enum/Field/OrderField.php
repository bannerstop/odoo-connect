<?php

namespace Bannerstop\OdooConnect\Enum\Field;

enum OrderField: string
{
    case ID = 'id';
    case ORDER_ID = 'name';
    case STATE = 'state';
    case SHOP_ORDER_ID = 'client_order_ref';
    case CUSTOMER = 'partner_id';
    case CUSTOMER_SHIPPING = 'partner_shipping_id';
    case CUSTOMER_INVOICE = 'partner_invoice_id';
    case ORDER_LINE = 'order_line';
    case AMOUNT_TOTAL = 'amount_total';
    case AMOUNT_UNTAXED = 'amount_untaxed';
    case AMOUNT_TAX = 'amount_tax';
    case AMOUNT_TO_INVOICE = 'amount_to_invoice';
    case TAG_IDS = 'tag_ids';
    case INVOICE_IDS = 'invoice_ids';
    case DATE_PRODUCTION = 'date_production';
    case LIFETIME = 'date_files';
    case CREATE_DATE = 'create_date';
}
