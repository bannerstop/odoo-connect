<?php

namespace Bannerstop\OdooConnect\Builders;

use Bannerstop\OdooConnect\Client\OdooClient;
use Bannerstop\OdooConnect\Enums\StateEnum;
use Bannerstop\OdooConnect\Mappers\ModelDTOMapper;
use Bannerstop\OdooConnect\Enums\ModelEnum;

class RequestBuilder
{
    private QueryBuilder $queryBuilder;
    private ModelEnum $model;

    public function __construct(
        private readonly OdooClient $client
    ) {
        $this->queryBuilder = new QueryBuilder();
    }

    public function model(ModelEnum $model): self
    {
        $this->model = $model;
        $this->queryBuilder->model($model->value);
        return $this;
    }

    public function where(string $field, string $operator, mixed $value): self
    {
        $this->queryBuilder->where($field, $operator, $value);
        return $this;
    }

    public function state(StateEnum $state): self
    {
        $this->queryBuilder->where('state', '=', $state->value);
        return $this;
    }

    public function fields(array $fields): self
    {
        $this->queryBuilder->fields($fields);
        return $this;
    }

    public function getRaw(): array
    {
        $model = $this->queryBuilder->getModel();
        $domain = $this->queryBuilder->getDomain();
        $fields = $this->queryBuilder->getFields();

        $endpoint = sprintf('/api/%s/search', $model);
        $queryParams = [
            'domain' => $domain,
            'fields' => $fields,
            'db' => $this->client->getConnection()->getDb(),
        ];

        return $this->client->request('GET', $endpoint, ['query' => $queryParams]);
    }

    public function get(): array
    {
        $rawData = $this->getRaw();
        return array_map(
            fn(array $item) => ModelDTOMapper::map($this->model, $item),
            $rawData
        );
    }
}
