<?php

namespace Bannerstop\OdooConnect\Mappers;

use Bannerstop\OdooConnect\DTO\OrderDTO;
use Bannerstop\OdooConnect\DTO\QuoteDTO;
use Bannerstop\OdooConnect\DTO\OrderLineDTO;
use Bannerstop\OdooConnect\DTO\InvoiceDTO;
use Bannerstop\OdooConnect\Enums\ModelEnum;
use InvalidArgumentException;

class ModelDTOMapper
{
    public static function map(ModelEnum $model, array $data): object
    {
        return match ($model) {
            ModelEnum::SALE_ORDER => self::mapSaleOrder($data),
            ModelEnum::SALE_ORDER_LINE => OrderLineDTO::fromArray($data),
            ModelEnum::ACCOUNT_MOVE => InvoiceDTO::fromArray($data),
            default => throw new InvalidArgumentException("No mapping defined for model: {$model->value}")
        };
    }

    private static function mapSaleOrder(array $data): object
    {
        if ($data['state'] === 'draft') {
            return QuoteDTO::fromArray($data); 
        }

        return OrderDTO::fromArray($data); 
    }
}
