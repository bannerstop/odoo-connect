<?php

namespace Bannerstop\OdooConnect\Services;

use Bannerstop\OdooConnect\Builders\RequestBuilder;
use Bannerstop\OdooConnect\Enums\ModelEnum;
use Bannerstop\OdooConnect\DTO\InvoiceDTO;
use Bannerstop\OdooConnect\Exceptions\OdooRecordNotFoundException;

class InvoiceService
{
    public function __construct(
        private readonly RequestBuilder $requestBuilder
    ) {}

    /**
     * Get invoices by shop order ID
     *
     * @param string $shopOrderId Shop order ID reference
     * @return array<InvoiceDTO> Returns array of InvoiceDTO objects
     * @throws \InvalidArgumentException When mapping fails or shop order ID is empty
     * @throws OdooRecordNotFoundException When no record is found
     */
    public function getInvoicesByShopOrderId(string $shopOrderId): array
    {
        if (empty($shopOrderId)) {
            throw new \InvalidArgumentException('Shop order ID cannot be null or empty');
        }

        return $this->requestBuilder
            ->model(ModelEnum::ACCOUNT_MOVE)
            ->where('ref', '=', $shopOrderId)
            ->get();
    }

    /**
     * Get invoice by Odoo invoice ID
     *
     * @param string $invoiceId Odoo invoice ID
     * @return InvoiceDTO Returns an InvoiceDTO object
     * @throws \InvalidArgumentException When mapping fails
     * @throws OdooRecordNotFoundException When no record is found
     */
    public function getInvoiceByInvoiceId(string $invoiceId): InvoiceDTO
    {
        return $this->requestBuilder
            ->model(ModelEnum::ACCOUNT_MOVE)
            ->where('name', '=', $invoiceId)
            ->get()[0];
    }
}
