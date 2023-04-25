<?php

declare(strict_types=1);

namespace App\Identity\Infrastructure\Customer\Persistence\Doctrine\Query;

use App\Identity\Application\Customer\Query\AllCustomerQuery as AllCustomerQueryInterface;
use App\Identity\Domain\Customer\AbstractCustomer;
use App\Identity\Domain\Customer\Customer;
use App\Identity\Domain\Customer\CustomerCollection;
use App\Identity\Domain\Customer\DeletedCustomer;
use App\Shared\Infrastructure\Doctrine\Query\SwitchableTenantBaseQuery;

final class AllCustomerQuery extends SwitchableTenantBaseQuery implements AllCustomerQueryInterface
{
    protected function getClass(): string
    {
        return Customer::class;
    }

    public function query(): CustomerCollection
    {
        $qb = $this->switchableActiveTenant
            ->entityManager()
            ->createQueryBuilder();
        $expr = $qb->expr();

        return new CustomerCollection(
            ...$qb
                ->select('c')
                ->from(AbstractCustomer::class, 'c')
                ->where($expr->not($expr->isInstanceOf('c', DeletedCustomer::class)))
                ->getQuery()
                ->getResult()
        );
    }
}
