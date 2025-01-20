<?php

namespace Bannerstop\OdooConnect\Mapper;

use Bannerstop\OdooConnect\DTO\CustomerDTO;
use Bannerstop\OdooConnect\DTO\OrderDTO;
use Bannerstop\OdooConnect\DTO\OrderItemDTO;
use Bannerstop\OdooConnect\DTO\InvoiceDTO;
use Bannerstop\OdooConnect\Enum\Model;
use InvalidArgumentException;

class ModelDTOMapper
{
    public static function map(Model $model, array $data): object
    {
        return match ($model) {
            Model::SALE_ORDER => OrderDTO::fromArray($data),
            Model::SALE_ORDER_LINE => OrderItemDTO::fromArray($data),
            Model::ACCOUNT_MOVE => InvoiceDTO::fromArray($data),
            Model::RES_PARTNER => CustomerDTO::fromArray($data),
            default => throw new InvalidArgumentException("No mapping defined for model: {$model->value}")
        };
    }
}
