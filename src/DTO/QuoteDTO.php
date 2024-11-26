<?php

namespace Bannerstop\OdooConnect\DTO;

use DateTimeImmutable;

class QuoteDTO
{
    public function __construct(
        private readonly string $id,
        private readonly string $state,
        private readonly DateTimeImmutable $createDate,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data["name"],
            state: $data['state'],
            createDate: new DateTimeImmutable($data['create_date']),
        );
    }
}
