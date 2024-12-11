<?php

namespace Bannerstop\OdooConnect\Mappers;

use Bannerstop\OdooConnect\DTO\CustomerDTO;
use Bannerstop\OdooConnect\DTO\OrderDTO;
use Bannerstop\OdooConnect\DTO\OrderLineDTO;
use Bannerstop\OdooConnect\DTO\InvoiceDTO;
use Bannerstop\OdooConnect\Enums\ModelEnum;
use InvalidArgumentException;

class ModelDTOMapper
{
    public static function map(ModelEnum $model, array $data): object
    {
        return match ($model) {
            ModelEnum::SALE_ORDER => OrderDTO::fromArray($data),
            ModelEnum::SALE_ORDER_LINE => OrderLineDTO::fromArray($data),
            ModelEnum::ACCOUNT_MOVE => InvoiceDTO::fromArray($data),
            ModelEnum::RES_PARTNER => CustomerDTO::fromArray($data),
            default => throw new InvalidArgumentException("No mapping defined for model: {$model->value}")
        };
    }
}
