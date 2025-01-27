<?php

namespace Bannerstop\OdooConnect\Enum\Field;

class OrderField
{
    const ID = 'id';
    const ORDER_ID = 'name';
    const STATE = 'state';
    const SHOP_ORDER_ID = 'client_order_ref';
    const CUSTOMER = 'partner_id';
    const CUSTOMER_SHIPPING = 'partner_shipping_id';
    const CUSTOMER_INVOICE = 'partner_invoice_id';
    const ORDER_LINE = 'order_line';
    const AMOUNT_TOTAL = 'amount_total';
    const AMOUNT_UNTAXED = 'amount_untaxed';
    const AMOUNT_TAX = 'amount_tax';
    const AMOUNT_TO_INVOICE = 'amount_to_invoice';
    const TAG_IDS = 'tag_ids';
    const INVOICE_IDS = 'invoice_ids';
    const DATE_PRODUCTION = 'date_production';
    const LIFETIME = 'date_files';
    const CREATE_DATE = 'create_date';
}
