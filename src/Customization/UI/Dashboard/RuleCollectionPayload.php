<?php

namespace App\Customization\UI\Dashboard;

use App\Customization\Domain\RuleCollection;
use App\Shared\UI\DashboardPayload;

class RuleCollectionPayload implements DashboardPayload
{
    private RuleCollection $rules;

    public function __construct(RuleCollection $rules)
    {
        $this->rules = $rules;
    }

    public function get(): RuleCollection
    {
        return $this->rules;
    }
}