<?php

namespace Bannerstop\OdooConnect\DTO;

use Bannerstop\OdooConnect\Config\Config;
use DateTimeImmutable;

class TrackingCodeDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $code,
        public readonly ?string $carrier,
        public readonly DateTimeImmutable $createDate,
        public readonly DateTimeImmutable $writeDate,
        public readonly ?string $spark,
    ) {}

    public static function fromArray(array $data, Config $config): self
    {
        return new self(
            id: $data["id"],
            name: $data["name"],
            code: $data["code"],
            carrier: $data["carrier"] ?? null,
            createDate: (new DateTimeImmutable($data['create_date'], $config->getOdooTimezone()))
                ->setTimezone($config->getReturnDataTimezone()),
            writeDate: (new DateTimeImmutable($data['write_date'], $config->getOdooTimezone()))
                ->setTimezone($config->getReturnDataTimezone()),
            spark: $data['spark'] ?: null,
        );
    }
}
