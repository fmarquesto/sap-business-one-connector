<?php

namespace fmarquesto\SapBusinessOneConnector\Repositories;

class PurchaseDeliveryNoteDocuments extends Documents
{
    public function endpoint(): string
    {
        return 'PurchaseDeliveryNotes';
    }
}
