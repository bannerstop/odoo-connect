<?php

namespace Bannerstop\OdooConnect\Services;

use Bannerstop\OdooConnect\Builders\RequestBuilder;
use Bannerstop\OdooConnect\Enums\ModelEnum;
use Bannerstop\OdooConnect\DTO\OrderDTO;
use Bannerstop\OdooConnect\DTO\OrderLineDTO;

class OrderService
{
    public function __construct(
        private readonly RequestBuilder $requestBuilder
    ) {}

    /**
     * Get order by its Odoo order ID
     *
     * @param string $orderId The Odoo order ID
     * @return array<OrderDTO> Returns array of OrderDTO objects
     * @throws \InvalidArgumentException When mapping fails
     */
    public function getOrderByOrderId(string $orderId): array
    {
        return $this->requestBuilder
            ->model(ModelEnum::SALE_ORDER)
            ->where('name', '=', $orderId)
            ->where('state', '!=', 'draft')
            ->get();
    }

    /**
     * Get orders within a date range
     *
     * @param string $startDate Start date in Y-m-d format
     * @param string $endDate End date in Y-m-d format
     * @return array<OrderDTO> Returns array of OrderDTO objects
     * @throws \InvalidArgumentException When mapping fails
     */
    public function getOrdersByDate(string $startDate, string $endDate): array
    {
        return $this->requestBuilder
            ->model(ModelEnum::SALE_ORDER)
            ->where('create_date', '>=', $startDate)
            ->where('create_date', '<=', $endDate)
            ->where('state', '!=', 'draft')
            ->get();
    }

    /**
     * Get order by shop's order ID
     *
     * @param string $shopOrderId The shop's order reference
     * @return array<OrderDTO> Returns array of OrderDTO objects
     * @throws \InvalidArgumentException When mapping fails
     */
    public function getOrderByShopOrderId(string $shopOrderId): array
    {
        return $this->requestBuilder
            ->model(ModelEnum::SALE_ORDER)
            ->where('client_order_ref', '=', $shopOrderId)
            ->where('state', '!=', 'draft')
            ->get();
    }

    /**
     * Get order line items for a specific order
     *
     * @param string $orderId The Odoo order ID
     * @return array<OrderLineDTO> Returns array of OrderLineDTO objects
     * @throws \InvalidArgumentException When mapping fails
     */
    public function getOrderItemsByOrderId(string $orderId): array
    {
        return $this->requestBuilder
            ->model(ModelEnum::SALE_ORDER_LINE)
            ->where('order_id.name', '=', $orderId)
            ->where('state', '!=', 'draft')
            ->get();
    }
}
