<?php

declare(strict_types=1);

namespace App\Identity\Infrastructure\Company;

use App\Identity\Application\Company\CompanyException;
use App\Identity\Application\Company\Query\CompanyByCriteriaQuery;
use App\Identity\Domain\Company\Company;
use App\Identity\Infrastructure\User\Persistence\Doctrine\Query\UserCompanyByUserQuery;
use App\Identity\Infrastructure\User\Security\AuthenticatedUser;
use App\Shared\Application\ActiveTenant as ActiveTenantAlias;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Security;

// todo think about to split Switchable&Active Tenants (remove inheritance)
// todo or instead merge two one SwitchableTenant
class SwitchableActiveTenant extends ActiveTenant implements ActiveTenantAlias
{
    public function __construct(RequestStack $requestStack,
                                ManagerRegistry $managerRegistry,
                                Security $security,
                                UserCompanyByUserQuery $userCompanyByUserQuery,
                                CompanyByCriteriaQuery $companyByCriteriaQuery)
    {
        try {
            parent::__construct(
                $requestStack,
                $managerRegistry,
                $security,
                $userCompanyByUserQuery,
                $companyByCriteriaQuery
            );
        } catch (CompanyException $exception) {

        }
    }

    protected function userClass(): AuthenticatedUser
    {
        return new AuthenticatedUser($this->security);
    }


    public function tenant(Company $company): void
    {
        $this->company = $company;
    }
}
