<?php

namespace fmarquesto\SapBusinessOneConnector\Connector;

use fmarquesto\SapBusinessOneConnector\Repositories\IRepository;

interface ISAPBusinessOneConnector
{
    public function getAll(IRepository $entity): array;

    public function getOneByKey(IRepository $entity, $key): array;

    public function getAllByFilter(IRepository $entity, string $filter): array;

    public function getFirstByFilter(IRepository $entity, string $filter): array;

    public function create(IRepository $entity, array $data): array;

    public function update(IRepository $entity, $key, array $data): void;

    public function delete(IRepository $entity, $key): void;

    public function updateByBatch(IRepository $entity, array $data): array;
}
