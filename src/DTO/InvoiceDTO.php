<?php

namespace Bannerstop\OdooConnect\DTO;

use DateTimeImmutable;

class InvoiceDTO
{
    public string $id;
    public ?float $amountTotal;
    public ?float $amountUntaxed;
    public ?float $amountResidual;
    public DateTimeImmutable $createDate;

    public function __construct(
        string $id,
        ?float $amountTotal,
        ?float $amountUntaxed,
        ?float $amountResidual,
        DateTimeImmutable $createDate
    ) {
        $this->id = $id;
        $this->amountTotal = $amountTotal;
        $this->amountUntaxed = $amountUntaxed;
        $this->amountResidual = $amountResidual;
        $this->createDate = $createDate;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data["name"],
            $data['amount_total'] ?? null,
            $data['amount_untaxed'] ?? null,
            $data['amount_residual'] ?? null,
            new DateTimeImmutable($data['create_date'])
        );
    }
}
