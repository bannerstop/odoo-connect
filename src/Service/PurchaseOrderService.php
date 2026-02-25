<?php

namespace Bannerstop\OdooConnect\Service;

use Bannerstop\OdooConnect\Builder\RequestBuilder;
use Bannerstop\OdooConnect\Enum\Field\PurchaseOrderField;
use Bannerstop\OdooConnect\Enum\Model;
use Bannerstop\OdooConnect\DTO\PurchaseOrderDTO;
use Bannerstop\OdooConnect\Enum\PurchaseState;
use Bannerstop\OdooConnect\Exception\OdooRecordNotFoundException;

use InvalidArgumentException;

class PurchaseOrderService
{
    public function __construct(
        private readonly RequestBuilder $requestBuilder
    ) {}

    /**
     * Get purchase order(s) by its Odoo purchase order name
     *
     * @param string $purchaseOrderName Odoo purchase order name
     * @param PurchaseOrderField[]|null $fields Fields to retrieve
     * @return PurchaseOrderDTO[]|array Array of PurchaseOrderDTO objects or an array with specified fields
     * @throws InvalidArgumentException When mapping fails
     * @throws OdooRecordNotFoundException When no record is found
     */
    public function getPurchaseOrdersByName(
        string $purchaseOrderName,
        ?array $fields = null,
        ?int $limit = null,
        ?int $offset = null,
        ?string $order = null,
    ): array {
        $request = $this->requestBuilder
            ->model(Model::PURCHASE_ORDER)
            ->where('name', '=', $purchaseOrderName);

        if ($limit !== null) {
            $request->limit($limit);
        }

        if ($offset !== null) {
            $request->offset($offset);
        }

        if ($order !== null) {
            $request->order($order);
        }

        if ($fields !== null) {
            $request->fields($fields);
            return $request->getRaw();
        }

        return $request->get();
    }

    /**
     * Get purchase orders within a date range
     *
     * @param string $startDate Start date in Y-m-d format
     * @param string $endDate End date in Y-m-d format
     * @param PurchaseState|null $state Optional purchase order state
     * @param PurchaseOrderField[]|null $fields Fields to retrieve
     * @return PurchaseOrderDTO[]|array Array of PurchaseOrderDTO objects or an array with specified fields
     * @throws InvalidArgumentException When mapping fails
     * @throws OdooRecordNotFoundException When no record is found
     */
    public function getPurchaseOrdersByDate(
        string $startDate,
        string $endDate,
        ?PurchaseState $state = null,
        ?array $fields = null,
        ?int $limit = null,
        ?int $offset = null,
        ?string $order = null,
    ): array {
        $request = $this->requestBuilder
            ->model(Model::PURCHASE_ORDER)
            ->where('date_order', '>=', $startDate)
            ->where('date_order', '<=', $endDate);

        if ($state !== null) {
            $request->where('state', '=', $state);
        }

        if ($limit !== null) {
            $request->limit($limit);
        }

        if ($offset !== null) {
            $request->offset($offset);
        }

        if ($order !== null) {
            $request->order($order);
        }

        if ($fields !== null) {
            $request->fields($fields);
            return $request->getRaw();
        }

        return $request->get();
    }
}
