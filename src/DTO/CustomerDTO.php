<?php

namespace Bannerstop\OdooConnect\DTO;

class CustomerDTO
{
    public function __construct(
        public readonly string $id,
        public readonly ?string $name,
        public readonly ?string $completeName,
        public readonly ?string $commercialCompanyName,
        public readonly ?string $email,
        public readonly ?string $street,
        public readonly ?string $street2,
        public readonly ?string $zip,
        public readonly ?string $city,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data["id"],
            name: $data["name"] ?? null,
            completeName: $data["complete_name"] ?? null,
            commercialCompanyName: $data["commercial_company_name"] ?? null,
            email: $data["email_normalized"] ?? null,
            street: $data["street"] ?? null,
            street2: $data["street2"] ?? null,
            zip: $data["zip"] ?? null,
            city: $data["city"] ?? null
        );
    }
}
