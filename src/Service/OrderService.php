<?php

namespace Bannerstop\OdooConnect\Service;

use Bannerstop\OdooConnect\Builder\RequestBuilder;
use Bannerstop\OdooConnect\Enum\Field\OrderField;
use Bannerstop\OdooConnect\Enum\Field\OrderItemField;
use Bannerstop\OdooConnect\Enum\Model;
use Bannerstop\OdooConnect\DTO\OrderDTO;
use Bannerstop\OdooConnect\DTO\OrderItemDTO;
use Bannerstop\OdooConnect\Enum\State;
use Bannerstop\OdooConnect\Exception\OdooRecordNotFoundException;
use InvalidArgumentException;

class OrderService
{
    private $requestBuilder;

    public function __construct(RequestBuilder $requestBuilder)
    {
        $this->requestBuilder = $requestBuilder;
    }

    /**
     * Get order by its Odoo order ID
     *
     * @param string $orderId Odoo order ID.
     * @param OrderField[]|null $fields Fields to retrieve
     * @return OrderDTO|array OrderDTO object or an array with specified fields
     * @throws InvalidArgumentException When mapping fails
     * @throws OdooRecordNotFoundException When no record is found
     */
    public function getOrderByOrderId(string $orderId, ?array $fields = null)
    {
        $request = $this->requestBuilder
            ->model(Model::SALE_ORDER)
            ->where('name', '=', $orderId);

        if ($fields !== null) {
            $request->fields($fields);
            return $request->getRaw()[0];
        }

        return $request->get()[0];
    }

    /**
     * Get orders within a date range
     *
     * @param string $startDate Start date in Y-m-d format
     * @param string $endDate End date in Y-m-d format
     * @param string|null $type Optional order type
     * @param OrderField[]|null $fields Fields to retrieve
     * @return OrderDTO[]|array Array of OrderDTO objects or an array of orders with specified fields
     * @throws InvalidArgumentException When mapping fails
     * @throws OdooRecordNotFoundException When no record is found
     */
    public function getOrdersByDate(string $startDate, string $endDate, ?string $type = null, ?array $fields = null): array
    {
        $request = $this->requestBuilder
            ->model(Model::SALE_ORDER)
            ->where('create_date', '>=', $startDate)
            ->where('create_date', '<=', $endDate);
    
        if ($type !== null) {
            $request->state($type);
        }

        if ($fields !== null) {
            $request->fields($fields);
            return $request->getRaw();
        }
    
        return $request->get();
    }

    /**
     * Get order by shop's order ID
     *
     * @param string $shopOrderId Shop's order reference
     * @param OrderField[]|null $fields Fields to retrieve
     * @return OrderDTO|array OrderDTO object or an array with specified fields
     * @throws InvalidArgumentException When mapping fails
     * @throws OdooRecordNotFoundException When no record is found
     */
    public function getOrderByShopOrderId(string $shopOrderId, ?array $fields = null)
    {
        $request = $this->requestBuilder
            ->model(Model::SALE_ORDER)
            ->where('client_order_ref', '=', $shopOrderId)
            ->where('state', '!=', 'draft');

        if ($fields !== null) {
            $request->fields($fields);
            return $request->getRaw()[0];
        }

        return $request->get()[0];
    }

    /**
     * Get order line items for a specific order
     *
     * @param string $orderId The Odoo order ID
     * @param OrderItemField[]|null $fields The fields to retrieve
     * @return OrderItemDTO[]|array Array of OrderItem objects or an array with specified fields
     * @throws InvalidArgumentException When mapping fails
     * @throws OdooRecordNotFoundException When no record is found
     */
    public function getOrderItemsByOrderId(string $orderId, ?array $fields = null): array
    {
        $request = $this->requestBuilder
            ->model(Model::SALE_ORDER_LINE)
            ->where('order_id.name', '=', $orderId);

        if ($fields !== null) {
            $request->fields($fields);
            return $request->getRaw();
        }

        return $request->get();
    }

    /**
     * Update order fields
     *
     * @param int $id The Odoo ID (not order ID)
     * @param array<OrderField, mixed> $fields Associative array of fields to update
     * @return bool True if update was successful
     * @throws OdooRecordNotFoundException When no record is found
     */
    public function updateOrderFields(int $id, array $fields): bool
    {
        return $this->requestBuilder
            ->model(Model::SALE_ORDER)
            ->recordId($id)
            ->updateFields($fields)
            ->update();
    }

    /**
     * Update order's last Jira sync timestamp
     *
     * @param int $id The Odoo ID (not order ID)
     * @return bool True if update was successful
     * @throws OdooRecordNotFoundException When no record is found
     */
    public function updateOrderLastJiraSync(int $id): bool
    {
        $currentTimestamp = (new \DateTime())->format('Y-m-d H:i:s');
        
        return $this->updateOrderFields(
            $id, 
            ['date_jira_last_sync' => $currentTimestamp]
        );
    }
}
