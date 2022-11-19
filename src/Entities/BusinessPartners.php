<?php

namespace fmarquesto\SapBusinessOneConnector\Entities;

use fmarquesto\SapBusinessOneConnector\Common\SelectProperties;
use fmarquesto\SapBusinessOneConnector\SAPConnector;

class BusinessPartners extends SAPConnector
{
    use SelectProperties;

    public function getAll(): array
    {
        // TODO: Implement getAll() method.
    }

    public function getOneByKey($key): array
    {
        // TODO: Implement getOneByKey() method.
    }

    public function getAllByFilter(string $filter): array
    {
        // TODO: Implement getAllByFilter() method.
    }

    public function getFirstByFilter(string $filter): array
    {
        // TODO: Implement getFirstByFilter() method.
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

    protected function endpoint(): string
    {
        return 'BusinessPartners';
    }

    protected function key(): string
    {
        return "CardCode";
    }

    protected function defaultSelect(): array
    {
        return ['CardCode', 'CardName'];
    }
}
