<?php

namespace Bannerstop\OdooConnect\DTO;

use DateTimeImmutable;
use DateTimeZone;

class OrderDTO
{
    public int $id;
    public string $orderId;
    public string $state;
    public string $shopOrderId;
    public int $customerId;
    public string $customerName;
    public int $customerShippingId;
    public int $customerInvoiceId;
    public float $amountTotal;
    public float $amountUntaxed;
    public float $amountTax;
    public float $amountToInvoice;
    public array $tagIds;
    public array $invoiceIds;
    public string $invoiceStatus;
    public ?DateTimeImmutable $dateProduction;
    public ?DateTimeImmutable $lifetime;
    public DateTimeImmutable $createDate;

    public function __construct(
        int $id,
        string $orderId,
        string $state,
        string $shopOrderId,
        int $customerId,
        string $customerName,
        int $customerShippingId,
        int $customerInvoiceId,
        float $amountTotal,
        float $amountUntaxed,
        float $amountTax,
        float $amountToInvoice,
        array $tagIds,
        array $invoiceIds,
        string $invoiceStatus,
        ?DateTimeImmutable $dateProduction,
        ?DateTimeImmutable $lifetime,
        DateTimeImmutable $createDate
    ) {
        $this->id = $id;
        $this->orderId = $orderId;
        $this->state = $state;
        $this->shopOrderId = $shopOrderId;
        $this->customerId = $customerId;
        $this->customerName = $customerName;
        $this->customerShippingId = $customerShippingId;
        $this->customerInvoiceId = $customerInvoiceId;
        $this->amountTotal = $amountTotal;
        $this->amountUntaxed = $amountUntaxed;
        $this->amountTax = $amountTax;
        $this->amountToInvoice = $amountToInvoice;
        $this->tagIds = $tagIds;
        $this->invoiceIds = $invoiceIds;
        $this->invoiceStatus = $invoiceStatus;
        $this->dateProduction = $dateProduction;
        $this->lifetime = $lifetime;
        $this->createDate = $createDate;
    }

    public static function fromArray(array $data): self
    {
        $timezone = new DateTimeZone('Europe/Berlin');
    
        return new self(
            $data["id"],
            $data["name"],
            $data["state"],
            $data['client_order_ref'],
            $data['partner_id'][0]["id"],
            $data['partner_id'][0]["name"],
            $data["partner_shipping_id"][0]["id"],
            $data["partner_invoice_id"][0]["id"],
            $data['amount_total'],
            $data['amount_untaxed'],
            $data['amount_tax'],
            $data['amount_to_invoice'],
            array_column($data['tag_ids'] ?? [], 'id'),
            array_column($data['invoice_ids'] ?? [], 'id'),
            $data['invoice_status'],
            $data['date_production'] ? new DateTimeImmutable($data['date_production'], $timezone) : null,
            $data['date_files'] ? new DateTimeImmutable($data['date_files'], $timezone) : null,
            new DateTimeImmutable($data['create_date'], $timezone)
        );
    }
}
