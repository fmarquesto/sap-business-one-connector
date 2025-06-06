<?php

namespace fmarquesto\SapBusinessOneConnector;

class QueryBuilder
{
    public function __construct(
        public readonly string $uri,
        public readonly string $key = '',
        public readonly array $params = [],
        private array $select = [],
        private array $filter = [],
        readonly private int $top = 0
    ) {
    }

    public function buildUrl(): string
    {
        $queryParams = $this->key !== ''? "($this->key)" : '';

        if(!empty($this->select)) {
            $queryParams .= '?' . '$select=' . implode(',', $this->select);
        }

        if(!empty($this->filter)) {
            $queryParams .= '&$filter=' . implode(' ', $this->filter);
        }

        if($this->top > 0) {
            $queryParams .= '&$top=' . $this->top;
        }

        return $this->uri . rawurlencode($queryParams);
    }

    public function addFilter(string ...$filter): self
    {
        $this->filter = array_merge($this->filter, $filter);

        return $this;
    }

    public function addSelect(string ...$fields): self
    {
        $this->select = array_merge($this->select, $fields);

        return $this;
    }
}
