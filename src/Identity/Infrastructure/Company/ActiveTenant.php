<?php

declare(strict_types=1);

namespace App\Identity\Infrastructure\Company;

use App\Identity\Application\Company\CompanyException;
use App\Identity\Application\Company\Query\CompanyByCriteriaQuery;
use App\Identity\Application\Company\Query\CompanyByIdCriteria;
use App\Identity\Application\User\Exception\UserException;
use App\Identity\Domain\Company\Company;
use App\Identity\Domain\Company\CompanyId;
use App\Identity\Infrastructure\User\Persistence\Doctrine\Query\UserCompanyByUserQuery;
use App\Shared\Application\BaseActiveTenant;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Security;

class ActiveTenant implements BaseActiveTenant
{
    private ?Request $request;

    private CompanyByCriteriaQuery $companyByCriteriaQuery;

    protected ?Company $company = null;

    private ManagerRegistry $registry;

    protected Security $security;

    public function __construct(RequestStack $requestStack,
                                ManagerRegistry $managerRegistry,
                                Security $security,
                                UserCompanyByUserQuery $userCompanyByUserQuery,
                                CompanyByCriteriaQuery $companyByCriteriaQuery)
    {
        $companyId = null;
        $company = null;
        $this->registry = $managerRegistry;
        $this->request = $requestStack->getCurrentRequest();
        $this->security = $security;
        $this->companyByCriteriaQuery = $companyByCriteriaQuery;

        $env = getenv('APP_COMPANY_KEY');

        if ($env) {
            $companyId = $env;
        }

        if ($this->request) {
            $companyId = $this->request->headers->get('X-App-Key');
        }

        if (!$companyId && $this instanceof SwitchableActiveTenant) {
            try {
                // fixme when Customer logged and no app key in we got auth exception there
                $user = $this->userClass();

                $company = $user->user()->activeCompany();
//                $company = $userCompanyByUserQuery->query($user->user());
//                $company = $userCompanyByUserQuery->query($user->customer());
            } catch (UserException $exception) {
                throw CompanyException::appKeyEmpty();
            }
        } else if ($companyId) {
            $companyId = new CompanyId($companyId);
            $queryCriteria = new CompanyByIdCriteria($companyId);

            $company = $this->companyByCriteriaQuery->queryOne($queryCriteria);
        }

        if (!$company) {
            throw CompanyException::companyNotFound();
        }

        $this->company = $company;
    }

    // todo generalize AuthenticatedUser & AuthenticatedCustomer interface and declare return type
//    public function userClass(): BaseAuthenticatedUser
//    abstract protected function userClass(): AuthenticatedUser;
//    protected function userClass()
//    {
//        return new AuthenticatedCustomer($this->security);
//    }

    public function entityManager(): EntityManagerInterface
    {
        return $this->registry->getManager($this->alias());
//        return clone $this->registry->getManager($this->alias());
    }

    public function company(): ?Company
    {
        return $this->company;
    }

    private function alias(): ?string
    {
        return $this->company() ? $this->company->aliasPrefixed() : null;
//        return $this->company->name()->alias();
    }
}
