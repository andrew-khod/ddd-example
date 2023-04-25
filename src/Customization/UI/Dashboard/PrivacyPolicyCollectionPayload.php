<?php

namespace App\Customization\UI\Dashboard;

use App\Customization\Domain\PrivacyPolicyCollection;
use App\Shared\UI\DashboardPayload;

class PrivacyPolicyCollectionPayload implements DashboardPayload
{
    private PrivacyPolicyCollection $collection;

    public function __construct(PrivacyPolicyCollection $collection)
    {
        $this->collection = $collection;
    }

    public function get(): PrivacyPolicyCollection
    {
        return $this->collection;
    }
}