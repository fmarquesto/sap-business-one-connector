<?php

namespace fmarquesto\SapBusinessOneConnector\Repositories;

class InvoiceDocuments extends Documents
{
    public function endpoint(): string
    {
        return 'Invoices';
    }
}
