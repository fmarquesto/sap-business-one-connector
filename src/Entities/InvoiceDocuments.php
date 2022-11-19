<?php

namespace fmarquesto\SapBusinessOneConnector\Entities;

class InvoiceDocuments extends Documents
{
    public function endpoint(): string
    {
        return 'Invoices';
    }
}
