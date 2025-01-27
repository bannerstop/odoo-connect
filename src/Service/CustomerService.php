<?php

namespace Bannerstop\OdooConnect\Service;

use Bannerstop\OdooConnect\Builder\RequestBuilder;
use Bannerstop\OdooConnect\DTO\CustomerDTO;
use Bannerstop\OdooConnect\Enum\Field\CustomerField;
use Bannerstop\OdooConnect\Enum\Model;
use Bannerstop\OdooConnect\Exception\OdooRecordNotFoundException;
use InvalidArgumentException;

class CustomerService
{
    private RequestBuilder $requestBuilder;

    public function __construct(RequestBuilder $requestBuilder)
    {
        $this->requestBuilder = $requestBuilder;
    }

    /**
     * Get customer by customer ID
     *
     * @param string $customerId Shop order ID reference
     * @param CustomerField[]|null $fields Fields to retrieve
     * @return CustomerDTO|array CustomerDTO object or an array with specified fields
     * @throws InvalidArgumentException When mapping fails
     * @throws OdooRecordNotFoundException When no record is found
     */
    public function getCustomerById(string $customerId, ?array $fields = null)
    {
        $request = $this->requestBuilder
            ->model(Model::RES_PARTNER)
            ->where('id', '=', $customerId);

        if ($fields !== null) {
            $request->fields($fields);
            return $request->getRaw()[0];
        }
        
        return $request->get()[0];
    }
}
