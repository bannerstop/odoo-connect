<?php

namespace Bannerstop\OdooConnect\Builder;

class QueryBuilder
{
    private string $model;
    private array $domain = [];
    private array $fields = [];
    private int $limit = 80;
    private int $offset = 0;
    private ?string $order = null;

    public function model(string $model): self
    {
        $this->model = $model;
        $this->domain = [];
        $this->fields = [];
        $this->limit = 80;
        $this->offset = 0;
        $this->order = null;
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
    
    public function limit(int $limit): self
    {
        $this->limit = $limit;
        return $this;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function offset(int $offset): self
    {
        $this->offset = $offset;
        return $this;
    }

    public function getOffset(): int
    {
        return $this->offset;
    }

    public function order(string $order): self
    {
        $this->order = $order;
        return $this;
    }

    public function getOrder(): ?string
    {
        return $this->order;
    }

    public function getModel(): string
    {
        return $this->model;
    }
}
