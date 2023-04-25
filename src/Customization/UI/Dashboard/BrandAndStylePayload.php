<?php

namespace App\Customization\UI\Dashboard;

use App\Identity\Domain\Company\Company;
use App\Shared\UI\DashboardPayload;

class BrandAndStylePayload implements DashboardPayload
{
    private Company $company;

    public function __construct(Company $company)
    {
        $this->company = $company;
    }

    public function get(): Company
    {
        return $this->company;
    }
}