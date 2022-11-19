<?php

namespace fmarquesto\SapBusinessOneConnector\Entities;

use fmarquesto\SapBusinessOneConnector\Common\SelectProperties;
use fmarquesto\SapBusinessOneConnector\SAPConnector;

class Items extends SAPConnector
{
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
