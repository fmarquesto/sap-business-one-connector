<?php

namespace fmarquesto\SapBusinessOneConnector\Entities;

class BusinessPartners extends Entity
{
    function endpoint(): string
    {
        return 'BusinessPartners';
    }

    function key(): string
    {
        return "CardCode";
    }

    function defaultSelect(): array
    {
        return ['CardCode', 'CardName'];
    }
}
