<?php

namespace Bannerstop\OdooConnect\Services;

use Bannerstop\OdooConnect\Builders\RequestBuilder;
use Bannerstop\OdooConnect\Enums\ModelEnum;
use Bannerstop\OdooConnect\Enums\StateEnum;

class QuoteService
{
    public function __construct(
        private readonly RequestBuilder $requestBuilder
    ) {}

    public function getQuoteByQuoteId(string $quoteId): array
    {
        return $this->requestBuilder
            ->model(ModelEnum::SALE_ORDER)
            ->state(StateEnum::QUOTE)
            ->where('name', '=', $quoteId)
            ->get();
    }

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
