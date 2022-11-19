<?php

namespace fmarquesto\SapBusinessOneConnector\Entities;

class OrderDocuments extends Documents
{
    public function endpoint(): string
    {
        return 'Orders';
    }
}
