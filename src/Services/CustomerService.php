<?php

namespace Bannerstop\OdooConnect\Services;

use Bannerstop\OdooConnect\Builders\RequestBuilder;
use Bannerstop\OdooConnect\DTO\CustomerDTO;
use Bannerstop\OdooConnect\Enums\ModelEnum;
use Bannerstop\OdooConnect\Exceptions\OdooRecordNotFoundException;

class CustomerService
{
    public function __construct(
        private readonly RequestBuilder $requestBuilder
    ) {}

    /**
     * Get customer by customer ID
     *
     * @param string $customerId Shop order ID reference
     * @return CustomerDTO Returns a CustomerDTO object
     * @throws \InvalidArgumentException When mapping fails
     * @throws OdooRecordNotFoundException When no record is found
     */
    public function getCustomerById(string $customerId): CustomerDTO
    {
        return $this->requestBuilder
            ->model(ModelEnum::RES_PARTNER)
            ->where('id', '=', $customerId)
            ->get()[0];
    }
}
