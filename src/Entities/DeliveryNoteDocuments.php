<?php

namespace fmarquesto\SapBusinessOneConnector\Entities;

class DeliveryNoteDocuments extends Documents
{
    function endpoint(): string
    {
        return 'DeliveryNotes';
    }
}
