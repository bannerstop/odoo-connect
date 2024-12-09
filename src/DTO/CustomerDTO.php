<?php

namespace Bannerstop\OdooConnect\DTO;

class CustomerDTO
{
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly string $email,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data["id"],
            name: $data["name"],
            email: $data["email_normalized"],
        );
    }
}
