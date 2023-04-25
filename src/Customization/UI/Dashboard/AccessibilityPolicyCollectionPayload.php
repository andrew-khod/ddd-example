<?php

namespace App\Customization\UI\Dashboard;

use App\Customization\Domain\AccessibilityPolicyCollection;
use App\Shared\UI\DashboardPayload;

class AccessibilityPolicyCollectionPayload implements DashboardPayload
{
    private AccessibilityPolicyCollection $policies;

    public function __construct(AccessibilityPolicyCollection $policies)
    {
        $this->policies = $policies;
    }

    public function get(): AccessibilityPolicyCollection
    {
        return $this->policies;
    }
}