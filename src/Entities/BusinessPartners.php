<?php

namespace fmarquesto\SapBusinessOneConnector\Entities;

use fmarquesto\SapBusinessOneConnector\Common\SelectProperties;
use fmarquesto\SapBusinessOneConnector\SAPConnector;

class BusinessPartners extends SAPConnector
{
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
