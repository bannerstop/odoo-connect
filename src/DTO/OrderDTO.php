<?php

namespace Bannerstop\OdooConnect\DTO;

use DateTimeImmutable;

class OrderDTO
{
    public function __construct(
        public readonly string $id,
        public readonly string $state,
        public readonly string $shopOrderId,
        public readonly string $customerId,
        public readonly string $customerName,
        public readonly float $amount,
        //public readonly float $lifetime,
        public readonly DateTimeImmutable $createDate,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data["name"],
            state: $data['state'],
            shopOrderId: $data['client_order_ref'],
            customerId: $data['partner_id'][0]["id"],
            customerName: $data['partner_id'][0]["name"],
            amount: $data['amount_total'],
            createDate: new DateTimeImmutable($data['create_date'])
        );
    }
}
