<?php

namespace fmarquesto\SapBusinessOneConnector\Entities;

class PurchaseDeliveryNoteDocuments extends Documents
{
    public function endpoint(): string
    {
        return 'PurchaseDeliveryNotes';
    }
}
