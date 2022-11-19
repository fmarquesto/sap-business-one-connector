<?php

namespace fmarquesto\SapBusinessOneConnector\Entities;

use fmarquesto\SapBusinessOneConnector\SAPConnector;

abstract class Documents extends SAPConnector
{
    protected function key(): string
    {
        return 'DocEntry';
    }

    protected function defaultSelect(): array
    {
        return ['DocEntry', 'DocNum'];
    }
}
