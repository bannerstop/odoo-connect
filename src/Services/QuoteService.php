<?php

namespace Bannerstop\OdooConnect\Services;

use Bannerstop\OdooConnect\Builders\RequestBuilder;
use Bannerstop\OdooConnect\Enums\ModelEnum;
use Bannerstop\OdooConnect\Enums\StateEnum;
use Bannerstop\OdooConnect\DTO\OrderDTO;
use Bannerstop\OdooConnect\Exceptions\OdooRecordNotFoundException;

/* not used for now */
class QuoteService
{
    public function __construct(
        private readonly RequestBuilder $requestBuilder
    ) {}

    /**
     * Get quote by its Odoo quote ID
     *
     * @param string $quoteId The Odoo quote ID
     * @return array<OrderDTO> Returns array of OrderDTO objects
     * @throws \InvalidArgumentException When mapping fails
     * @throws OdooRecordNotFoundException When no record is found
     */
    public function getQuoteByQuoteId(string $quoteId): array
    {
        return $this->requestBuilder
            ->model(ModelEnum::SALE_ORDER)
            ->state(StateEnum::QUOTE)
            ->where('name', '=', $quoteId)
            ->get();
    }

    /**
     * Get quotes within a date range
     *
     * @param string $startDate Start date in Y-m-d format
     * @param string $endDate End date in Y-m-d format
     * @return array<OrderDTO> Returns array of OrderDTO objects
     * @throws \InvalidArgumentException When mapping fails
     * @throws OdooRecordNotFoundException When no record is found
     */
    public function getQuotesByDate(string $startDate, string $endDate): array
    {
        return $this->requestBuilder
            ->model(ModelEnum::SALE_ORDER)
            ->state(StateEnum::QUOTE)
            ->where('create_date', '>=', $startDate)
            ->where('create_date', '<=', $endDate)
            ->get();
    }
}
