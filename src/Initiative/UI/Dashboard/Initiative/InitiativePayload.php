<?php

declare(strict_types=1);

namespace App\Initiative\UI\Dashboard\Initiative;

use App\Initiative\Domain\Initiative\Initiative;
use App\Shared\UI\DashboardPayload;

class InitiativePayload implements DashboardPayload
{
    private Initiative $initiative;

    public function __construct(Initiative $initiative)
    {
        $this->initiative = $initiative;
    }

    public function get(): Initiative
    {
        return $this->initiative;
    }
}