<?php

declare(strict_types=1);

namespace App\Identity\Infrastructure\Customer\Persistence\Doctrine\Query;

use App\Identity\Application\Customer\Query\BannedCustomerByCriteriaQuery as BannedCustomerByCriteriaQueryInterface;
use App\Identity\Application\Customer\Query\CustomerCriteria;
use App\Identity\Domain\Customer\BannedCustomer;
use App\Identity\Domain\Customer\CustomerCollection;
use App\Shared\Infrastructure\Doctrine\Query\SwitchableTenantBaseQuery;

final class BannedCustomerByCriteriaQuery extends SwitchableTenantBaseQuery implements BannedCustomerByCriteriaQueryInterface
{
    protected function getClass(): string
    {
        return BannedCustomer::class;
    }

    public function queryOne(CustomerCriteria $criteria): ?BannedCustomer
    {
        return $this->findOneByCriteria($criteria);
    }

    public function queryMultiple($criteria): CustomerCollection
    {
        return new CustomerCollection(...$this->findMultipleByCriteriaV2($criteria));
    }
}
