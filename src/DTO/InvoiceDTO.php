<?php

namespace Bannerstop\OdooConnect\DTO;

use Bannerstop\OdooConnect\Utils\DateTimeHelper;
use DateTimeImmutable;

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
        return new self(
            id: $data["name"],
            amountTotal: $data['amount_total'] ?? null,
            amountUntaxed: $data['amount_untaxed'] ?? null,
            amountResidual: $data['amount_residual'] ?? null,
            createDate: DateTimeHelper::createFromString($data['create_date'])
        );
    }
}
