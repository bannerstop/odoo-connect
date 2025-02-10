<?php

namespace Bannerstop\OdooConnect\DTO;

use Bannerstop\OdooConnect\Enum\InvoiceStatus;
use Bannerstop\OdooConnect\Enum\State;
use Bannerstop\OdooConnect\Utils\DateTimeHelper;
use DateTimeImmutable;

class OrderDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $orderId,
        public readonly State $state,
        public readonly string $shopOrderId,
        public readonly int $customerId,
        public readonly string $customerName,
        public readonly int $customerShippingId,
        public readonly int $customerInvoiceId,
        public readonly float $amountTotal,
        public readonly float $amountUntaxed,
        public readonly float $amountTax,
        public readonly float $amountToInvoice,
        public readonly array $tagIds,
        public readonly array $invoiceIds,
        public readonly int $itemCount,
        public readonly InvoiceStatus $invoiceStatus,
        public readonly ?DateTimeImmutable $dateProofAcceptance,
        public readonly ?DateTimeImmutable $dateProduction,
        public readonly ?DateTimeImmutable $lifetime,
        public readonly DateTimeImmutable $createDate,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data["id"],
            orderId: $data["name"],
            state: State::from($data['state']),
            shopOrderId: $data['client_order_ref'],
            customerId: $data['partner_id'][0]["id"],
            customerName: $data['partner_id'][0]["name"],
            customerShippingId: $data["partner_shipping_id"][0]["id"],
            customerInvoiceId: $data["partner_invoice_id"][0]["id"],
            amountTotal: $data['amount_total'],
            amountUntaxed: $data['amount_untaxed'],
            amountTax: $data['amount_tax'],
            amountToInvoice: $data['amount_to_invoice'],
            tagIds: array_column($data['tag_ids'] ?? [], 'id'),
            invoiceIds: array_column($data['invoice_ids'] ?? [], 'id'),
            itemCount: isset($data['order_line']) ? count($data['order_line']) : 0,
            invoiceStatus: InvoiceStatus::from($data['invoice_status']),
            dateProofAcceptance: DateTimeHelper::createFromString(dateString: $data['date_proof_acceptance'], hourOffset: null),
            dateProduction: DateTimeHelper::createFromString(dateString: $data['date_production']),
            lifetime: DateTimeHelper::createFromString(dateString: $data['date_files']),
            createDate: DateTimeHelper::createFromString(dateString: $data['create_date'])
        );
    }
}
