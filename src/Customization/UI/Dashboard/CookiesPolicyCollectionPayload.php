<?php

namespace App\Customization\UI\Dashboard;

use App\Customization\Domain\CookiesPolicyCollection;
use App\Shared\UI\DashboardPayload;

class CookiesPolicyCollectionPayload implements DashboardPayload
{
    private CookiesPolicyCollection $policies;

    public function __construct(CookiesPolicyCollection $policies)
    {
        $this->policies = $policies;
    }

    public function get(): CookiesPolicyCollection
    {
        return $this->policies;
    }
}