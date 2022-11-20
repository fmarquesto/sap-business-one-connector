<?php

namespace fmarquesto\SapBusinessOneConnector\Repositories;

use fmarquesto\SapBusinessOneConnector\Common\SelectProperties;
use fmarquesto\SapBusinessOneConnector\Connector\SAPManager;

abstract class Repository implements IRepository
{
    use SelectProperties;

    protected SAPManager $SAPManager;

    public function __construct(SAPManager $SAPManager)
    {
        $this->SAPManager = $SAPManager;
    }
    public function getAll(): array
    {
        return $this->SAPManager->getAll($this);
    }

    public function getOneByKey($key): array
    {
        return $this->SAPManager->getOneByKey($this, $key);
    }

    public function getAllByFilter(string $filter): array
    {
        return $this->SAPManager->getAllByFilter($this, $filter);
    }

    public function getFirstByFilter(string $filter): array
    {
        return $this->SAPManager->getFirstByFilter($this, $filter);
    }

    public function create(array $data): array
    {
        return $this->SAPManager->create($this, $data);
    }

    public function update($key, array $data): void
    {
        $this->SAPManager->update($this, $key, $data);
    }

    public function delete($key): void
    {
        $this->SAPManager->delete($this, $key);
    }

    public function updateByBatch(array $data): array
    {
        return $this->SAPManager->updateByBatch($this, $data);
    }
}
