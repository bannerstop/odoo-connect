<?php

namespace Bannerstop\OdooConnect\Service;

use Bannerstop\OdooConnect\Builder\RequestBuilder;
use Bannerstop\OdooConnect\Enum\Field\InvoiceField;
use Bannerstop\OdooConnect\Enum\Model;
use Bannerstop\OdooConnect\DTO\InvoiceDTO;
use Bannerstop\OdooConnect\Exception\OdooRecordNotFoundException;
use InvalidArgumentException;

class InvoiceService
{
    public function __construct(
        private readonly RequestBuilder $requestBuilder
    ) {}

    /**
     * Get invoices by shop order ID
     *
     * @param string $shopOrderId Shop order ID reference
     * @param InvoiceField[]|null $fields Fields to retrieve
     * @return InvoiceDTO[]|array Array of InvoiceDTO objects or an array of invoices with specified fields
     * @throws InvalidArgumentException When mapping fails or shop order ID is empty
     * @throws OdooRecordNotFoundException When no record is found
     */
    public function getInvoicesByShopOrderId(
        string $shopOrderId,
        ?array $fields = null,
        ?int $limit = null,
        ?int $offset = null,
        ?string $order = null,
    ): array {
        if (empty($shopOrderId)) {
            throw new InvalidArgumentException('Shop order ID cannot be null or empty');
        }

        $request = $this->requestBuilder
            ->model(Model::ACCOUNT_MOVE)
            ->where('ref', '=', $shopOrderId);

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
     * Get invoice by Odoo invoice ID
     *
     * @param string $invoiceId Odoo invoice ID
     * @param InvoiceField[]|null $fields Fields to retrieve
     * @return InvoiceDTO|array InvoiceDTO object or an array with specified fields
     * @throws InvalidArgumentException When mapping fails
     * @throws OdooRecordNotFoundException When no record is found
     */
    public function getInvoiceByInvoiceId(string $invoiceId, ?array $fields = null): InvoiceDTO|array
    {
        $request =  $this->requestBuilder
            ->model(Model::ACCOUNT_MOVE)
            ->where('name', '=', $invoiceId);

        if ($fields !== null) {
            $request->fields($fields);
            return $request->getRaw()[0];
        }
        
        return $request->get()[0];
    }

    /**
     * Update invoice's spark field
     *
     * @param int $id Odoo internal ID
     * @param string|false $spark Spark value to set, or false to clear
     * @return bool True if update was successful
     * @throws OdooRecordNotFoundException When no record is found
     */
    public function updateSpark(int $id, string|false $spark): bool
    {
        return $this->requestBuilder
            ->model(Model::ACCOUNT_MOVE)
            ->recordId($id)
            ->updateFields([InvoiceField::SPARK->value => $spark])
            ->update();
    }
}
