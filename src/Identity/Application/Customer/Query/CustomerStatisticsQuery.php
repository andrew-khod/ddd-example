<?php

namespace App\Identity\Application\Customer\Query;

use App\Identity\Domain\Customer\Customer;
use App\Identity\Domain\Customer\Statistics;

interface CustomerStatisticsQuery
{
    public function query(Customer $customer): Statistics;
}