<?php

namespace fmarquesto\SapBusinessOneConnector\Repositories;

class Manufacturers extends Entity
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
