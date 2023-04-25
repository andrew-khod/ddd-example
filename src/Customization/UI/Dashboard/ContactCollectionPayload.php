<?php

namespace App\Customization\UI\Dashboard;

use App\Customization\Domain\ContactCollection;
use App\Shared\UI\DashboardPayload;

class ContactCollectionPayload implements DashboardPayload
{
    private ContactCollection $contacts;

    public function __construct(ContactCollection $contacts)
    {
        $this->contacts = $contacts;
    }

    public function get(): ContactCollection
    {
        return $this->contacts;
    }
}