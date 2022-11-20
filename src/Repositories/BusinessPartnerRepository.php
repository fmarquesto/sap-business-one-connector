<?php

namespace fmarquesto\SapBusinessOneConnector\Repositories;

class BusinessPartnerRepository extends Repository
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
