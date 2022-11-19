<?php

namespace fmarquesto\SapBusinessOneConnector\Entities;

class DeliveryNoteDocuments extends Documents
{
    protected function endpoint(): string
    {
        return 'DeliveryNotes';
    }
}
