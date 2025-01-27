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
    public static function map(string $model, array $data): object
    {
        switch ($model) {
            case Model::SALE_ORDER:
                return OrderDTO::fromArray($data);
            case Model::SALE_ORDER_LINE:
                return OrderItemDTO::fromArray($data);
            case Model::ACCOUNT_MOVE:
                return InvoiceDTO::fromArray($data);
            case Model::RES_PARTNER:
                return CustomerDTO::fromArray($data);
            default:
                throw new InvalidArgumentException("No mapping defined for model: {$model}");
        }
    }
}
