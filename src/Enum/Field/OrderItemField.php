<?php

namespace Bannerstop\OdooConnect\Enum\Field;

enum OrderItemField: string
{
    case PRODUCT = 'product_id';
    case PRODUCT_DESCRIPTION = 'name';
    case UNIT = 'product_uom';
    case PRICE_UNIT = 'price_unit';
    case PRICE_TOTAL = 'price_total';
    case PRICE_SUBTOTAL = 'price_subtotal';
    case QUANTITY = 'product_uom_qty';
}
