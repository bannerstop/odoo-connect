<?php

namespace Bannerstop\OdooConnect\DTO;

class OrderItemDTO
{
    public ?string $productName;
    public ?int $productId;
    public ?string $productDescription;
    public ?string $unit;
    public ?int $unitId;
    public ?float $priceUnit;
    public ?float $priceTotal;
    public ?float $priceSubtotal;
    public ?float $quantity;

    public function __construct(
        ?string $productName,
        ?int $productId,
        ?string $productDescription,
        ?string $unit,
        ?int $unitId,
        ?float $priceUnit,
        ?float $priceTotal,
        ?float $priceSubtotal,
        ?float $quantity
    ) {
        $this->productName = $productName;
        $this->productId = $productId;
        $this->productDescription = $productDescription;
        $this->unit = $unit;
        $this->unitId = $unitId;
        $this->priceUnit = $priceUnit;
        $this->priceTotal = $priceTotal;
        $this->priceSubtotal = $priceSubtotal;
        $this->quantity = $quantity;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['product_id'][0]['name'] ?? null,
            $data['product_id'][0]['id'] ?? null,
            $data['name'] ?? null,
            $data['product_uom'][0]['name'] ?? null,
            $data['product_uom'][0]['id'] ?? null,
            $data['price_unit'] ?? null,
            $data['price_total'] ?? null,
            $data['price_subtotal'] ?? null,
            $data['product_uom_qty'] ?? null
        );
    }
}
