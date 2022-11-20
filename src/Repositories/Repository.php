<?php

namespace fmarquesto\SapBusinessOneConnector\Repositories;

use fmarquesto\SapBusinessOneConnector\Common\SelectProperties;
use fmarquesto\SapBusinessOneConnector\Connector\ISAPBusinessOneConnector;

abstract class Repository implements IRepository
{
    use SelectProperties;

    protected ISAPBusinessOneConnector $connector;

    public function __construct(ISAPBusinessOneConnector $connector)
    {
        $this->connector = $connector;
    }
    public function getAll(): array
    {
        return $this->connector->getAll($this);
    }

    public function getOneByKey($key): array
    {
        return $this->connector->getOneByKey($this, $key);
    }

    public function getAllByFilter(string $filter): array
    {
        return $this->connector->getAllByFilter($this, $filter);
    }

    public function getFirstByFilter(string $filter): array
    {
        return $this->connector->getFirstByFilter($this, $filter);
    }

    public function create(array $data): array
    {
        return $this->connector->create($this, $data);
    }

    public function update($key, array $data): void
    {
        $this->connector->update($this, $key, $data);
    }

    public function delete($key): void
    {
        $this->connector->delete($this, $key);
    }

    public function updateByBatch(array $data): array
    {
        return $this->connector->updateByBatch($this, $data);
    }
}
