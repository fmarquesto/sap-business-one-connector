<?php

namespace fmarquesto\SapBusinessOneConnector\Entities;

use fmarquesto\SapBusinessOneConnector\Common\SelectProperties;
use fmarquesto\SapBusinessOneConnector\SAPConnector;

class Items extends SAPConnector
{
    public function update($key, array $data):void
    {
        parent::update("'$key'", $data);
    }

    public function delete($key): void
    {
        parent::delete("'$key'");
    }

    protected function endpoint(): string
    {
        return 'Items';
    }

    protected function key(): string
    {
        return "'ItemCode'";
    }

    protected function defaultSelect(): array
    {
        return ['ItemCode', 'ItemName'];
    }
}
