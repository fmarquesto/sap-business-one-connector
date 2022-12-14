<?php

namespace fmarquesto\SapBusinessOneConnector\Repositories;

class ItemRepository extends Repository
{
    public function getOneByKey($key): array
    {
        return parent::getOneByKey("'$key'");
    }

    public function update($key, array $data):void
    {
        parent::update("'$key'", $data);
    }

    public function delete($key): void
    {
        parent::delete("'$key'");
    }

    function endpoint(): string
    {
        return 'Items';
    }

    function key(): string
    {
        return "'ItemCode'";
    }

    function defaultSelect(): array
    {
        return ['ItemCode', 'ItemName'];
    }
}
