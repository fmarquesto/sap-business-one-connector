<?php

namespace fmarquesto\SapBusinessOneConnector\Repositories;

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
