<?php

namespace fmarquesto\SapBusinessOneConnector\Repositories;

class ManufacturerRepository extends Repository
{
    public function endpoint(): string
    {
        return 'Manufacturers';
    }

    public function key(): string
    {
        return 'Code';
    }

    public function defaultSelect(): array
    {
        return ['Code', 'ManufacturerName'];
    }
}
