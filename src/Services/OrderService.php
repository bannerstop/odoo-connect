<?php

namespace Bannerstop\OdooConnect\Services;

use Bannerstop\OdooConnect\Builders\RequestBuilder;
use Bannerstop\OdooConnect\Enums\ModelEnum;
use Bannerstop\OdooConnect\DTO\OrderDTO;
use Bannerstop\OdooConnect\DTO\OrderLineDTO;
use Bannerstop\OdooConnect\Exceptions\OdooRecordNotFoundException;

class OrderService
{
    public function __construct(
        private readonly RequestBuilder $requestBuilder
    ) {}

    /**
     * Get order by its Odoo order ID
     *
     * @param string $orderId The Odoo order ID
     * @return OrderDTO Returns an OrderDTO object
     * @throws \InvalidArgumentException When mapping fails
     * @throws OdooRecordNotFoundException When no record is found
     */
    public function getOrderByOrderId(string $orderId): OrderDTO
    {
        return $this->requestBuilder
            ->model(ModelEnum::SALE_ORDER)
            ->where('name', '=', $orderId)
            ->get()[0];
    }

    /**
     * Get orders within a date range
     *
     * @param string $startDate Start date in Y-m-d format
     * @param string $endDate End date in Y-m-d format
     * @return array<OrderDTO> Returns array of OrderDTO objects
     * @throws \InvalidArgumentException When mapping fails
     * @throws OdooRecordNotFoundException When no record is found
     */
    public function getOrdersByDate(string $startDate, string $endDate): array
    {
        return $this->requestBuilder
            ->model(ModelEnum::SALE_ORDER)
            ->where('create_date', '>=', $startDate)
            ->where('create_date', '<=', $endDate)
            ->get();
    }

    /**
     * Get order by shop's order ID
     *
     * @param string $shopOrderId The shop's order reference
     * @return OrderDTO Returns an OrderDTO object
     * @throws \InvalidArgumentException When mapping fails
     * @throws OdooRecordNotFoundException When no record is found
     */
    public function getOrderByShopOrderId(string $shopOrderId): OrderDTO
    {
        return $this->requestBuilder
            ->model(ModelEnum::SALE_ORDER)
            ->where('client_order_ref', '=', $shopOrderId)
            ->where('state', '!=', 'draft')
            ->get()[0];
    }

    /**
     * Get order line items for a specific order
     *
     * @param string $orderId The Odoo order ID
     * @return array<OrderLineDTO> Returns array of OrderLineDTO objects
     * @throws \InvalidArgumentException When mapping fails
     * @throws OdooRecordNotFoundException When no record is found
     */
    public function getOrderItemsByOrderId(string $orderId): array
    {
        return $this->requestBuilder
            ->model(ModelEnum::SALE_ORDER_LINE)
            ->where('order_id.name', '=', $orderId)
            ->where('state', '!=', 'draft')
            ->get();
    }

    /**
     * Update order fields
     *
     * @param int $id The Odoo ID (not order ID)
     * @param array<string, mixed> $fields Associative array of fields to update
     * @return bool Returns true if update was successful
     * @throws OdooRecordNotFoundException When no record is found
     */
    public function updateOrderFields(int $id, array $fields): bool
    {
        return $this->requestBuilder
            ->model(ModelEnum::SALE_ORDER)
            ->recordId($id)
            ->updateFields($fields)
            ->update();
    }

    /**
     * Update order's last Jira sync timestamp
     *
     * @param int $id The Odoo ID (not order ID)
     * @return bool Returns true if update was successful
     * @throws OdooRecordNotFoundException When no record is found
     */
    public function updateOrderLastJiraSync(int $id): bool
    {
        $currentTimestamp = (new \DateTime())->format('Y-m-d H:i:s');
        
        return $this->updateOrderFields(
            $id, 
            ['x-jira-status' => $currentTimestamp]
        );
    }
}
