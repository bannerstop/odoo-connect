<?php

namespace Bannerstop\OdooConnect\Builder;

use Bannerstop\OdooConnect\Client\OdooClient;
use Bannerstop\OdooConnect\Enum\State;
use Bannerstop\OdooConnect\Mapper\ModelDTOMapper;
use Bannerstop\OdooConnect\Enum\Model;

class RequestBuilder
{
    private QueryBuilder $queryBuilder;
    private Model $model;
    private ?string $recordId = null;
    private array $updateFields = [];

    public function __construct(
        private readonly OdooClient $client
    ) {
        $this->queryBuilder = new QueryBuilder();
    }

    public function model(Model $model): self
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

    public function state(State $state): self
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

    public function recordId(string $id): self
    {
        $this->recordId = $id;
        return $this;
    }
    
    public function updateFields(array $fields): self
    {
        $this->updateFields = $fields;
        return $this;
    }

    public function update(): bool
    {
        if (!$this->recordId) {
            throw new \InvalidArgumentException('Record ID is required for update');
        }
        
        $endpoint = sprintf('/api/%s/%s', $this->model->value, $this->recordId);
        
        $this->client->request('PUT', $endpoint, [
            'query' => ['db' => $this->client->getConnection()->getDb()],
            'json' => $this->updateFields
        ]);
        
        return true;
    }
}
