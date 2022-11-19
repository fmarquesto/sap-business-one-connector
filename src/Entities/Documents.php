<?php

namespace fmarquesto\SapBusinessOneConnector\Entities;

abstract class Documents extends Entity
{
    function key(): string
    {
        return 'DocEntry';
    }

    function defaultSelect(): array
    {
        return ['DocEntry', 'DocNum'];
    }
}
