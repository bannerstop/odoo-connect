<?php

namespace Bannerstop\OdooConnect\Services;

use Bannerstop\OdooConnect\Builders\RequestBuilder;
use Bannerstop\OdooConnect\Enums\ModelEnum;
use Bannerstop\OdooConnect\DTO\InvoiceDTO;

class InvoiceService
{
    public function __construct(
        private readonly RequestBuilder $requestBuilder
    ) {}

    /**
     * Get invoice by shop order ID
     *
     * @param string $orderId Shop order ID reference
     * @return array<InvoiceDTO> Returns array of InvoiceDTO objects
     * @throws \InvalidArgumentException When mapping fails
     */
    public function getInvoiceByShopOrderId(string $orderId): array
    {
        return $this->requestBuilder
            ->model(ModelEnum::ACCOUNT_MOVE)
            ->where('ref', '=', $orderId)
            ->get();
    }

    /**
     * Get invoice by Odoo invoice ID
     *
     * @param string $invoiceId Odoo invoice ID
     * @return array<InvoiceDTO> Returns array of InvoiceDTO objects
     * @throws \InvalidArgumentException When mapping fails
     */
    public function getInvoiceByInvoiceId(string $invoiceId): array
    {
        return $this->requestBuilder
            ->model(ModelEnum::ACCOUNT_MOVE)
            ->where('name', '=', $invoiceId)
            ->get();
    }
}
