<?php

namespace App\Identity\Application\Customer\Query;

use App\Identity\Domain\Customer\BannedCustomer;

interface BannedCustomerByCriteriaQuery
{
    public function queryOne(CustomerCriteria $criteria): ?BannedCustomer;

//    public function queryMultiple($criteria): CustomerCollection;
}
