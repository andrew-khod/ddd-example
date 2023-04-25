<?php

namespace App\Identity\Application\Customer\Query;

use App\Identity\Domain\Customer\AbstractCustomer;
use App\Identity\Domain\Customer\Customer;
use App\Identity\Domain\Customer\CustomerCollection;

interface CustomerByCriteriaQuery
{
    public function queryOne(CustomerCriteria $criteria): ?AbstractCustomer;
//    public function queryOne(CustomerCriteria $criteria): ?Customer;
    public function queryMultiple($criteria): CustomerCollection;
}
