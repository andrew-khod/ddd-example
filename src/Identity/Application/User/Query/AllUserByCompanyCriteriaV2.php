<?php

namespace App\Identity\Application\User\Query;

//class AllUserCriteriaV2 implements UserCriteria
use App\Identity\Domain\Company\Company;
use App\Shared\Application\ActiveTenant;
use Happyr\DoctrineSpecification\Spec;
use Happyr\DoctrineSpecification\Specification\BaseSpecification;

class AllUserByCompanyCriteriaV2 extends BaseSpecification
{
    private Company $company;

    public function __construct(ActiveTenant $tenant, ?string $context = null)
    {
        parent::__construct($context);

        $this->company = $tenant->company();
    }

    protected function getSpec()
    {
        return Spec::andX(
//            Spec::addSelect('companies', 'permissions'),
            Spec::leftJoin('companies', 'companies'),
            Spec::leftJoin('permissions', 'permissions'),
//            Spec::addSelect(
//                Spec::selectEntity('companies'),
//                Spec::selectEntity('permissions'),
//            ),
            Spec::eq(Spec::field('company'), $this->company->id()->toBinary(), 'companies'),
//            Spec::eq(Spec::alias('companies.company'), $this->company->id()->toBinary(), 'companies'),
            Spec::eq(Spec::field('company'), $this->company->id()->toBinary(), 'permissions'),
//            Spec::eq(Spec::alias('permissions.company'), $this->company->id()->toBinary(), 'permissions'),
        );
    }
}