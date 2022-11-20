<?php

namespace fmarquesto\SapBusinessOneConnector\Repositories;

class InvoiceDocumentRepository extends DocumentRepository
{
    public function endpoint(): string
    {
        return 'Invoices';
    }
}
