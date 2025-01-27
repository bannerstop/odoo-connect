<?php

namespace Bannerstop\OdooConnect\DTO;

class CustomerDTO
{
    public string $id;
    public ?string $name;
    public ?string $completeName;
    public ?string $commercialCompanyName;
    public ?string $email;
    public ?string $street;
    public ?string $street2;
    public ?string $zip;
    public ?string $city;

    public function __construct(
        string $id,
        ?string $name,
        ?string $completeName,
        ?string $commercialCompanyName,
        ?string $email,
        ?string $street,
        ?string $street2,
        ?string $zip,
        ?string $city
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->completeName = $completeName;
        $this->commercialCompanyName = $commercialCompanyName;
        $this->email = $email;
        $this->street = $street;
        $this->street2 = $street2;
        $this->zip = $zip;
        $this->city = $city;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data["id"],
            $data["name"] ?? null,
            $data["complete_name"] ?? null,
            $data["commercial_company_name"] ?? null,
            $data["email_normalized"] ?? null,
            $data["street"] ?? null,
            $data["street2"] ?? null,
            $data["zip"] ?? null,
            $data["city"] ?? null
        );
    }
}
