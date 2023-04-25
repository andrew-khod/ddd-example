<?php

namespace App\Identity\Application\Customer\Query;

use App\Identity\Domain\Customer\CustomerCollection;

interface AllCustomerQuery
{
    public function query(): CustomerCollection;
}
