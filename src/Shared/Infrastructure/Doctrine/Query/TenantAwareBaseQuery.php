<?php

namespace App\Shared\Infrastructure\Doctrine\Query;

use App\Identity\Infrastructure\Company\ActiveTenant;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;

abstract class TenantAwareBaseQuery extends BaseQuery
{
    public function __construct(ManagerRegistry $managerRegistry, PaginatorInterface $paginator, ActiveTenant $activeTenant)
    {
        parent::__construct($managerRegistry, $paginator);

        $this->repository = $activeTenant
            ->entityManager()
            ->getRepository($this->getClass());
    }
}
