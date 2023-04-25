<?php

namespace App\Customization\UI\Dashboard;

use App\Customization\Domain\TermsOfUseCollection;
use App\Shared\UI\DashboardPayload;

class TermsOfUseCollectionPayload implements DashboardPayload
{
    private TermsOfUseCollection $terms;

    public function __construct(TermsOfUseCollection $terms)
    {
        $this->terms = $terms;
    }

    public function get(): TermsOfUseCollection
    {
        return $this->terms;
    }
}