<?php

namespace Bannerstop\OdooConnect\DTO;

use Bannerstop\OdooConnect\Config\Config;
use Bannerstop\OdooConnect\Enum\InvoiceStatus;
use Bannerstop\OdooConnect\Enum\PurchaseState;
use DateTimeImmutable;

class PurchaseOrderDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly PurchaseState $state,
        public readonly int $partnerId,
        public readonly string $partnerName,
        public readonly ?string $partnerRef,
        public readonly float $amountTotal,
        public readonly float $amountUntaxed,
        public readonly float $amountTax,
        public readonly array $orderLine,
        public readonly DateTimeImmutable $dateOrder,
        public readonly ?DateTimeImmutable $dateApprove,
        public readonly DateTimeImmutable $createDate,
        public readonly ?DateTimeImmutable $dateDelivery,
        public readonly ?string $notes,
        public readonly InvoiceStatus $invoiceStatus,
    ) {}

    public static function fromArray(array $data, Config $config): self
    {
        return new self(
            id: $data["id"],
            name: $data["name"],
            state: PurchaseState::safeFrom($data['state']),
            partnerId: $data['partner_id'][0]["id"],
            partnerName: $data['partner_id'][0]["name"],
            partnerRef: $data['partner_ref'] ?? null,
            amountTotal: $data['amount_total'],
            amountUntaxed: $data['amount_untaxed'],
            amountTax: $data['amount_tax'],
            orderLine: array_column($data['order_line'] ?? [], 'id'),
            dateOrder: (new DateTimeImmutable($data['date_order'], $config->getOdooTimezone()))
                ->setTimezone($config->getReturnDataTimezone()),
            dateApprove: $data['date_approve']
                ? (new DateTimeImmutable($data['date_approve'], $config->getOdooTimezone()))
                    ->setTimezone($config->getReturnDataTimezone())
                : null,
            createDate: (new DateTimeImmutable($data['create_date'], $config->getOdooTimezone()))
                ->setTimezone($config->getReturnDataTimezone()),
            dateDelivery: $data['date_planned']
                ? (new DateTimeImmutable($data['date_planned'], $config->getOdooTimezone()))
                    ->setTimezone($config->getReturnDataTimezone())
                : null,
            notes: $data['notes'] ?? null,
            invoiceStatus: InvoiceStatus::safeFrom($data['invoice_status']),
        );
    }
}
