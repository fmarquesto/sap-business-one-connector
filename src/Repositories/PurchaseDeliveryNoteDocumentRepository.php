<?php

namespace fmarquesto\SapBusinessOneConnector\Repositories;

class PurchaseDeliveryNoteDocumentRepository extends DocumentRepository
{
    public function endpoint(): string
    {
        return 'PurchaseDeliveryNotes';
    }
}
