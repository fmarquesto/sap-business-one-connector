<?php

namespace fmarquesto\SapBusinessOneConnector\Repositories;

class DeliveryNoteDocuments extends Documents
{
    function endpoint(): string
    {
        return 'DeliveryNotes';
    }
}
