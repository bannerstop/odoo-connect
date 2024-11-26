<?php

namespace Bannerstop\OdooConnect\Services;

use Bannerstop\OdooConnect\Builders\RequestBuilder;
use Bannerstop\OdooConnect\Enums\ModelEnum;

class InvoiceService
{
    public function __construct(
        private readonly RequestBuilder $requestBuilder
    ) {}

    public function getInvoiceByShopOrderId(string $orderId): array
    {
        return $this->requestBuilder
            ->model(ModelEnum::ACCOUNT_MOVE)
            ->where('ref', '=', $orderId)
            ->get();
    }

    public function getInvoiceByInvoiceId(string $invoiceId): array
    {
        return $this->requestBuilder
            ->model(ModelEnum::ACCOUNT_MOVE)
            ->where('name', '=', $invoiceId)
            ->get();
    }
}
