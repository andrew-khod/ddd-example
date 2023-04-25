<?php

namespace App\Identity\Infrastructure\Customer\Persistence\Doctrine;

use App\Identity\Domain\Customer\NotActivatedCustomer;
use App\Identity\Infrastructure\Company\SwitchableActiveTenant;
use Doctrine\Persistence\ManagerRegistry;

final class NotActivatedCustomerIdentityByTenantRepository extends CustomerIdentityByTenantRepository
{
//    public function __construct(ManagerRegistry $registry, ActiveTenant $activeTenant)
    public function __construct(ManagerRegistry $registry, SwitchableActiveTenant $activeTenant)
    {
        parent::__construct($registry, $activeTenant, NotActivatedCustomer::class);
    }
}
