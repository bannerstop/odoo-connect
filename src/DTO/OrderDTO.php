<?php

namespace Bannerstop\OdooConnect\DTO;

use Bannerstop\OdooConnect\Enums\StateEnum;
use DateTimeImmutable;

class OrderDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $orderId,
        public readonly StateEnum $state,
        public readonly string $shopOrderId,
        public readonly string $customerId,
        public readonly string $customerName,
        public readonly float $amountTotal,
        public readonly float $amountUntaxed,
        public readonly float $amountTax,
        public readonly float $amountToInvoice,
        public readonly array $tagIds,
        public readonly ?DateTimeImmutable $dateProduction,
        public readonly ?DateTimeImmutable $lifetime,
        public readonly DateTimeImmutable $createDate,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data["id"],
            orderId: $data["name"],
            state: StateEnum::from($data['state']),
            shopOrderId: $data['client_order_ref'],
            customerId: $data['partner_id'][0]["id"],
            customerName: $data['partner_id'][0]["name"],
            amountTotal: $data['amount_total'],
            amountUntaxed: $data['amount_untaxed'],
            amountTax: $data['amount_tax'],
            amountToInvoice: $data['amount_to_invoice'],
            tagIds: array_column($data['tag_ids'] ?? [], 'id'),
            dateProduction: $data['date_production'] ? new DateTimeImmutable($data['date_production']) : null,
            lifetime: $data['date_files'] ? new DateTimeImmutable($data['date_files']) : null,
            createDate: new DateTimeImmutable($data['create_date'])
        );
    }
}
