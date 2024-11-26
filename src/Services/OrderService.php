<?php

namespace Bannerstop\OdooConnect\Services;

use Bannerstop\OdooConnect\Builders\RequestBuilder;
use Bannerstop\OdooConnect\Enums\ModelEnum;

class OrderService
{
    public function __construct(
        private readonly RequestBuilder $requestBuilder
    ) {}

    public function getOrderByOrderId(string $orderId): array
    {
        return $this->requestBuilder
            ->model(ModelEnum::SALE_ORDER)
            ->where('name', '=', $orderId)
            ->get();
    }

    public function getOrdersByDate(string $startDate, string $endDate): array
    {
        return $this->requestBuilder
            ->model(ModelEnum::SALE_ORDER)
            ->where('create_date', '>=', $startDate)
            ->where('create_date', '<=', $endDate)
            ->get();
    }

    public function getOrderByShopOrderId(string $shopOrderId): array
    {
        return $this->requestBuilder
            ->model(ModelEnum::SALE_ORDER)
            ->where('client_order_ref', '=', $shopOrderId)
            ->get();
    }

    public function getOrderItemsByOrderId(string $orderId): array
    {
        return $this->requestBuilder
            ->model(ModelEnum::SALE_ORDER_LINE)
            ->where('order_id.name', '=', $orderId)
            ->get();
    }
}
