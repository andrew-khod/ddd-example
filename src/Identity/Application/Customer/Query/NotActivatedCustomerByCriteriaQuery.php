<?php

namespace App\Identity\Application\Customer\Query;

use App\Identity\Domain\Customer\NotActivatedCustomer;

interface NotActivatedCustomerByCriteriaQuery
{
    public function queryOne(CustomerCriteria $criteria): ?NotActivatedCustomer;

//    public function queryMultiple(CustomerCriteria $criteria): UserCollection;
    public function queryMultiple($criteria);
}
