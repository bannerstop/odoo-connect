<?php

namespace Bannerstop\OdooConnect\Builder;

class QueryBuilder
{
    private string $model;
    private array $domain = [];
    private array $fields = [];

    public function model(string $model): self
    {
        $this->model = $model;
        $this->domain = [];
        $this->fields = [];
        return $this;
    }

    public function where(string $field, string $operator, mixed $value): self
    {
        $this->domain[] = [$field, $operator, $value];
        return $this;
    }

    public function getDomain(): string
    {
        return json_encode($this->domain, JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES);
    }

    
    public function fields(array $fields): self
    {
        $this->fields = $fields;
        return $this;
    }

    public function getFields(): string
    {
        return json_encode($this->fields, JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES);
    }
    
    public function getModel(): string
    {
        return $this->model;
    }
}
