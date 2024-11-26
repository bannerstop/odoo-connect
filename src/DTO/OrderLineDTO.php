<?php

namespace Bannerstop\OdooConnect\DTO;


class OrderLineDTO
{
    public function __construct(
        private readonly string $name,
        private readonly ?string $unit,
        private readonly float $quantity
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            unit: $data['product_uom'][0]['name'] ?? null,
            quantity: $data['product_uom_qty'],
        );
    }
}
