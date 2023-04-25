<?php

declare(strict_types=1);

namespace App\Identity\Infrastructure\Customer\Persistence\Doctrine\Query;

use App\Identity\Application\Customer\Query\CustomerByCriteriaQuery as CustomerByCriteriaQueryInterface;
use App\Identity\Application\Customer\Query\CustomerCriteria;
use App\Identity\Domain\Customer\AbstractCustomer;
use App\Identity\Domain\Customer\Customer;
use App\Identity\Domain\Customer\CustomerCollection;
use App\Shared\Infrastructure\Doctrine\Query\SwitchableTenantBaseQuery;

final class CustomerByCriteriaQuery extends SwitchableTenantBaseQuery implements CustomerByCriteriaQueryInterface
{
    private string $class = Customer::class;

    protected function getClass(): string
    {
//        return Customer::class;
//        return AbstractCustomer::class;
        return $this->class;
    }

//    public function queryOne(CustomerCriteria $criteria): ?AbstractCustomer
    public function queryOne(CustomerCriteria $criteria): ?Customer
    {
        return $this->findOneByCriteria($criteria);
    }

    public function queryMultiple($criteria): CustomerCollection
    {
        return new CustomerCollection(...$this->findMultipleByCriteriaV2($criteria));
    }
}
