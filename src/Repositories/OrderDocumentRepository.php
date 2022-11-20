<?php

namespace fmarquesto\SapBusinessOneConnector\Repositories;

class OrderDocumentRepository extends DocumentRepository
{
    public function endpoint(): string
    {
        return 'Orders';
    }
}
