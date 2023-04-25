<?php

declare(strict_types=1);

namespace App\Identity\Infrastructure\Customer\Persistence\Doctrine\Query;

use App\Identity\Application\Customer\Query\CustomerCriteria;
use App\Identity\Application\Customer\Query\NotActivatedCustomerByCriteriaQuery as NotActivatedCustomerByCriteriaQueryInterface;
use App\Identity\Domain\Customer\NotActivatedCustomer;
use App\Shared\Infrastructure\Doctrine\Query\SwitchableTenantBaseQuery;

final class NotActivatedCustomerByCriteriaQuery extends SwitchableTenantBaseQuery implements NotActivatedCustomerByCriteriaQueryInterface
{
    protected function getClass(): string
    {
        return NotActivatedCustomer::class;
    }

    public function queryOne(CustomerCriteria $criteria): ?NotActivatedCustomer
    {
        return $this->findOneByCriteria($criteria);
    }

//    public function queryMultiple(CustomerCriteria $criteria): UserCollection
    public function queryMultiple($criteria)
    {
        return $this->findMultipleByCriteriaV2($criteria);
//        return new UserCollection(...$this->findMultipleByCriteria($criteria));
    }
}
