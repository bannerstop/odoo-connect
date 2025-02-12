<?php

namespace Bannerstop\OdooConnect\DTO;

use DateTimeImmutable;
use DateTimeZone;

class InvoiceDTO
{
    public function __construct(
        public readonly string $id,
        public readonly ?float $amountTotal,
        public readonly ?float $amountUntaxed,
        public readonly ?float $amountResidual,
        public readonly DateTimeImmutable $createDate,
    ) {}

    public static function fromArray(array $data): self
    {
        $timezone = new DateTimeZone('UTC');

        return new self(
            id: $data["name"],
            amountTotal: $data['amount_total'] ?? null,
            amountUntaxed: $data['amount_untaxed'] ?? null,
            amountResidual: $data['amount_residual'] ?? null,
            createDate: new DateTimeImmutable($data['create_date'], $timezone)
        );
    }
}
