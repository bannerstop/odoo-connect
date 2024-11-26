<?php

namespace Bannerstop\OdooConnect\DTO;

use DateTimeImmutable;

class InvoiceDTO
{
    public function __construct(
        private readonly string $id,
        private readonly ?float $amountTotal,
        private readonly ?float $amountUntaxed,
        private readonly ?float $amountResidual,
        private readonly DateTimeImmutable $createDate,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data["name"],
            amountTotal: $data['amount_total'] ?? null,
            amountUntaxed: $data['amount_untaxed'] ?? null,
            amountResidual: $data['amount_residual'] ?? null,
            createDate: new DateTimeImmutable($data['create_date']),
        );
    }
}
