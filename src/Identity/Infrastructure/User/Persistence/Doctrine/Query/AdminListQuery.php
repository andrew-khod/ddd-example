<?php

declare(strict_types=1);

namespace App\Identity\Infrastructure\User\Persistence\Doctrine\Query;

use App\Identity\Application\User\AuthenticatedUser;
use App\Identity\Domain\User\User;
use App\Identity\Domain\User\UserCollection;
use App\Shared\Application\ActiveTenant;
use App\Shared\Infrastructure\Doctrine\Query\BaseQuery;
use Doctrine\ORM\Query;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;

//final class UserByCriteriaQuery extends SwitchableTenantBaseQuery implements UserByCriteriaQueryInterface
final class AdminListQuery extends BaseQuery
{
    protected array $filters = [
        'softdeleteable' => true,
    ];
    private ActiveTenant $tenant;
    private AuthenticatedUser $authenticatedUser;
    private bool $superadmins = true;

    public function __construct(ManagerRegistry $managerRegistry,
                                PaginatorInterface $paginator,
                                AuthenticatedUser $authenticatedUser,
                                ActiveTenant $tenant)
    {
        parent::__construct($managerRegistry, $paginator);

        $this->tenant = $tenant;
        $this->authenticatedUser = $authenticatedUser;
    }

    protected function getClass(): string
    {
        return User::class;
    }

    public function query(): UserCollection
    {
        $qb = $this->createQueryBuilder('u');
//        $expr = $qb->expr();
        return new UserCollection(
            ...$qb
            ->addSelect('p', 'c', 'p1')
            ->leftJoin('u.companies', 'c', Join::WITH, 'c.company = :company')
//            ->join('u.companies', 'c', Join::WITH, 'c.company = :company')
            ->leftJoin('u.permissions', 'p', Join::WITH, 'p.company = :company')
            ->leftJoin('p.permission', 'p1')
//            ->andWhere('u.is_superadmin = 1 OR c.company is not null')
            ->andWhere('u.is_superadmin = :superadmin OR c.company is not null')
//            ->andWhere('c.company = :company')
//            ->andWhere('p.company = :company')
            ->setParameter('company', $this->tenant->company()->id()->toBinary())
            ->setParameter('superadmin', $this->superadmins)
            ->getQuery()
            ->setHint(Query::HINT_REFRESH, true)
            ->getResult()
        );
    }

    public function includeSuperadmins(): void
    {
        $this->superadmins = true;
    }

    public function excludeSuperadmins(): void
    {
        $this->superadmins = false;
    }
}
