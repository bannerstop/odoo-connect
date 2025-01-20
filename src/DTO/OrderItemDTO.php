<?php

namespace Bannerstop\OdooConnect\DTO;


class OrderItemDTO
{
    public function __construct(
        public readonly ?string $productName,
        public readonly ?int $productId,
        public readonly ?string $productDescription,
        public readonly ?string $unit,
        public readonly ?int $unitId,
        public readonly ?float $priceUnit,
        public readonly ?float $priceTotal,
        public readonly ?float $priceSubtotal,
        public readonly ?float $quantity
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            productName: $data['product_id'][0]['name'] ?? null,
            productId: $data['product_id'][0]['id'] ?? null,
            productDescription: $data['name'] ?? null,
            unit: $data['product_uom'][0]['name'] ?? null,
            unitId: $data['product_uom'][0]['id'] ?? null,
            priceUnit: $data['price_unit'] ?? null,
            priceTotal: $data['price_total'] ?? null,
            priceSubtotal: $data['price_subtotal'] ?? null,
            quantity: $data['product_uom_qty'] ?? null,
        );
    }
}
