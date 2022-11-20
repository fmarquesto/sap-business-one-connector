<?php

namespace fmarquesto\SapBusinessOneConnector\Connector;

use fmarquesto\SapBusinessOneConnector\Repositories\IEntity;

interface ISAPConnector
{
    public function getAll(IEntity $entity): array;

    public function getOneByKey(IEntity $entity, $key): array;

    public function getAllByFilter(IEntity $entity, string $filter): array;

    public function getFirstByFilter(IEntity $entity, string $filter): array;

    public function create(IEntity $entity, array $data): array;

    public function update(IEntity $entity, $key, array $data): void;

    public function delete(IEntity $entity, $key): void;

    public function updateByBatch(IEntity $entity, array $data): array;
}
