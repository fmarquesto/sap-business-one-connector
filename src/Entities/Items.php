<?php

namespace fmarquesto\SapBusinessOneConnector\Entities;

use fmarquesto\SapBusinessOneConnector\Common\SelectProperties;
use fmarquesto\SapBusinessOneConnector\SAPConnector;

class Items extends SAPConnector
{
    use SelectProperties;

    protected function endpoint(): string
    {
        return 'Items';
    }

    protected function key(): string
    {
        return "ItemCode";
    }

    protected function defaultSelect(): array
    {
        return ['ItemCode', 'ItemName'];
    }

    public function getAll(): array
    {
        return $this->get($this->endpoint());
    }

    public function getOneByKey($key): array
    {
        return $this->get($this->endpoint() . "('$key')");
    }

    public function getAllByFilter(string $filter): array
    {
        $this->filter = rawurlencode($filter);
        return $this->getAll();
    }

    public function getFirstByFilter(string $filter): array
    {
        $this->top = '&$top=1';
        return $this->getAllByFilter($filter);
    }

    public function create(array $data): array
    {
        // TODO: Implement create() method.
    }

    public function update($key, $data): void
    {
        // TODO: Implement update() method.
    }

    public function delete($key): void
    {
        // TODO: Implement delete() method.
    }
}
