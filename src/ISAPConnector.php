<?php

namespace fmarquesto\SapBusinessOneConnector;

interface ISAPConnector
{
    public function setSelect(string $property): ISAPConnector;

    public function setMultipleSelect(array $properties): ISAPConnector;

    public function getAll(): array;

    public function getOneByKey($key): array;

    public function getAllByFilter(string $filter): array;

    public function getFirstByFilter(string $filter): array;

    public function create(array $data): array;

    public function update($key, array $data): void;

    public function delete($key): void;

    public function updateByBatch(array $data): array;
}
