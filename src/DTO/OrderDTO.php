<?php

namespace Bannerstop\OdooConnect\DTO;

use Bannerstop\OdooConnect\Enum\State;
use DateTimeImmutable;
use DateTimeZone;

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
        public readonly ?DateTimeImmutable $dateProduction,
        public readonly ?DateTimeImmutable $lifetime,
        public readonly DateTimeImmutable $createDate,
    ) {}

    public static function fromArray(array $data): self
    {
        $timezone = new DateTimeZone('Europe/Berlin');

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
            dateProduction: $data['date_production'] ? new DateTimeImmutable($data['date_production'], $timezone) : null,
            lifetime: $data['date_files'] ? new DateTimeImmutable($data['date_files'], $timezone) : null,
            createDate: new DateTimeImmutable($data['create_date'], $timezone)
        );
    }
}
