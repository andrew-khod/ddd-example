<?php

namespace App\Shared\Infrastructure\Doctrine\Query;

use App\Identity\Domain\Company\Company;
use App\Identity\Infrastructure\Company\SwitchableActiveTenant;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Happyr\DoctrineSpecification\Repository\EntitySpecificationRepositoryTrait;
use Knp\Component\Pager\PaginatorInterface;

// fixme merge with BaseQuery and clear redundant code
abstract class SwitchableTenantBaseQuery extends BaseQuery
{
    // fixme a lot of public methods exposed
    use EntitySpecificationRepositoryTrait;

    protected array $filters = [
        'softdeleteable' => false,
    ];

    protected SwitchableActiveTenant $switchableActiveTenant;

    public function __construct(ManagerRegistry $managerRegistry, PaginatorInterface $paginator, SwitchableActiveTenant $switchableActiveTenant)
    {
        parent::__construct($managerRegistry, $paginator);

        $this->switchableActiveTenant = $switchableActiveTenant;

        if ($switchableActiveTenant->company()) {
            $this->tenant($switchableActiveTenant->company());
        }
    }

// todo add tenant() to interface
//    public function tenant(Company $company): EntityManager
//    public function tenant(Company $company): void
    public function tenant(Company $company): SwitchableActiveTenant
    {
        $this->switchableActiveTenant->tenant($company);
        $em = $this->switchableActiveTenant->entityManager();

        $this->repository = new ($em->getConfiguration()
            ->getDefaultRepositoryClassName())($em, $em->getClassMetadata($this->getClass()));
//            ->getRepository($this->getClass());

        return $this->switchableActiveTenant;
//        return $em;
    }

    protected function createQueryBuilder($alias, $indexBy = null): QueryBuilder
    {
        $em = $this->switchableActiveTenant->entityManager();
        $filters = $em->getFilters();

        foreach ($this->filters as $filter => $value) {
            if ($value && !$filters->isEnabled($filter)) {
                $filters->enable($filter);
                continue;
            }

            if (!$value && $filters->isEnabled($filter)) {
                $filters->disable($filter);
            }
        }

        return $em->createQueryBuilder()
                ->select($alias)
                ->from($this->getClass(), $alias, $indexBy);
    }
}
