<?php

namespace fmarquesto\SapBusinessOneConnector\Repositories;

abstract class DocumentRepository extends Repository
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
