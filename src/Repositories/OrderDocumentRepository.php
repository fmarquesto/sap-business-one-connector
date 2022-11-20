<?php

namespace fmarquesto\SapBusinessOneConnector\Repositories;

class OrderDocuments extends Documents
{
    public function endpoint(): string
    {
        return 'Orders';
    }
}
