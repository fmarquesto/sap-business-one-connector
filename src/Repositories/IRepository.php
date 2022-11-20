<?php

namespace fmarquesto\SapBusinessOneConnector\Repositories;

interface IRepository
{
    public function setSelect(string $property): IRepository;

    public function setMultipleSelect(array $properties): IRepository;

    function endpoint(): string;

    function key(): string;

    /**
     * This should be setted in order to avoid requesting unnecessary data
     * @return array
     */
    function defaultSelect(): array;

    public function getAll(): array;

    public function getOneByKey($key): array;

    public function getAllByFilter(string $filter): array;

    public function getFirstByFilter(string $filter): array;

    public function create(array $data): array;

    public function update($key, array $data): void;

    public function delete($key): void;

    public function updateByBatch(array $data): array;
}
