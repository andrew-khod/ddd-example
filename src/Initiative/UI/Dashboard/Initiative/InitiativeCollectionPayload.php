<?php

declare(strict_types=1);

namespace App\Initiative\UI\Dashboard\Initiative;

use App\Initiative\Domain\Initiative\InitiativeCollection;
use App\Shared\UI\DashboardPayload;

class InitiativeCollectionPayload implements DashboardPayload
{
    private InitiativeCollection $initiatives;

    public function __construct(InitiativeCollection $initiatives)
    {
        $this->initiatives = $initiatives;
    }

    public function get(): InitiativeCollection
    {
        return $this->initiatives;
    }
}