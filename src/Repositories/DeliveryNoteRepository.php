<?php

namespace fmarquesto\SapBusinessOneConnector\Repositories;

class DeliveryNoteRepository extends DocumentRepository
{
    function endpoint(): string
    {
        return 'DeliveryNotes';
    }
}
