<?php

namespace App\Identity\Infrastructure\User\Persistence\Doctrine;

use App\Identity\Application\Company\Query\AllCompanyCriteria;
use App\Identity\Application\Company\Query\CompanyByCriteriaQuery;
use App\Identity\Domain\Role\Role;
use App\Identity\Domain\Role\RoleCollection;
use App\Identity\Domain\User\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;

class UserIdentityByTenantRepository extends ServiceEntityRepository implements UserLoaderInterface
{
    // TODO refactor
    const USER_COMPANY_FIELDS = [
        'user_id',
        'company_id',
        'email',
        'is_active_company',
    ];

    private ManagerRegistry $registry;
    private CompanyByCriteriaQuery $companyByCriteriaQuery;

    public function __construct(ManagerRegistry $registry, CompanyByCriteriaQuery $companyByCriteriaQuery)
    {
        parent::__construct($registry, User::class);

        $this->registry = $registry;
        $this->companyByCriteriaQuery = $companyByCriteriaQuery;
    }

    public function loadUserByUsername(string $username)
    {
        return $this->findOneBy(['email' => $username]);
    }

    // TODO refactor
    public function findOneBy(array $criteria, array $orderBy = null)
    {
        $userCompanyFieldsRequested = $this->filterUserCompanyFields($criteria);
        $userFieldsRequested = $this->filterUserFields($criteria);
        $userIdentityRequested = $this->filterUserIdentityField($criteria);

        if (!count($userCompanyFieldsRequested) && !$userIdentityRequested) {
            return null;
        }

        if (!count($userCompanyFieldsRequested)) {
            $userCompanyFieldsRequested = $userIdentityRequested;
        }

        $defaultManager = $this->getEntityManager();
        $roles = $defaultManager->getRepository(Role::class)->findAll();

//        $userCompany = $defaultManager
//            ->getRepository(UserCompany::class)
//            ->findOneBy($userCompanyFieldsRequested);
//
//        if (!$userCompany) {
//            return null;
//        }
//
//        $company = $userCompany->company();
//        $tenantManager = $this->registry->getManager($company->aliasPrefixed());
//        $tenantManager = $this->registry->getManager($company->name()->alias());

//        $filters = $tenantManager->getFilters();
        $filters = $this->_em->getFilters();

        if (!$filters->isEnabled('softdeleteable')) {
            $filters->enable('softdeleteable');
//            $tenantManager->getFilters()->enable('softdeleteable');
        }

//        $userId = $userCompany->userId();

        /** @var User $user */
        $user = $this->_em->createQueryBuilder()
//            ->select('u', 'p')
//            ->select('u', 'p', 'c')
            ->select('u', 'p', 'c')
//            ->select('u', 'p', 'CASE WHEN (u.is_superadmin = 1) THEN ac ELSE c END')
            ->from(User::class, 'u')
            ->leftJoin('u.permissions', 'p', Join::WITH, 'p.company = u.activeCompany')
//            ->leftJoin(UserCompany::class, 'c', Join::WITH, 'c.user=u')
            ->leftJoin('u.companies', 'c')
//            ->join(Company::class, 'ac')
//            ->leftJoin('u.companies', 'c', Join::WITH, 'OR 1=1')
            ->where(sprintf('u.%s = :email', key($userCompanyFieldsRequested)))
            ->setParameter('email', $userCompanyFieldsRequested[key($userCompanyFieldsRequested)])
            ->getQuery()
//            ->getResult();
            ->getSingleResult();
//        $user = parent::findOneBy($userCompanyFieldsRequested);
//        $user = $tenantManager->find(User::class, $userId);

        if ($user) {
            // fixme inject roles using Reflection API
            $user->availableRoles(new RoleCollection(...$roles));

            //fixme hydrate by using DQL above instead of injecting companies by setter
            //fixme if superadmin hydrate User::UserCompanies by all rows from Company table, instead by assigned rows from UserCompany
            if ($user->isSuperAdmin()) {
                $companies = $this->companyByCriteriaQuery->queryMultiple(new AllCompanyCriteria());
                $user->allCompanies($companies);
            }
        }

        return $user;
    }

    private function filterUserCompanyFields(array $criteria): array
    {
        return array_filter(
            $criteria,
            fn ($k) => in_array($k, self::USER_COMPANY_FIELDS),
            ARRAY_FILTER_USE_KEY
        );
    }

    private function filterUserIdentityField(array $criteria)
    {
        return key_exists('id', $criteria) ? [
            'user_id' => $criteria['id'],
        ] : null;
    }

    private function filterUserFields(array $criteria): array
    {
        return array_filter($criteria, fn ($k) => !in_array($k, self::USER_COMPANY_FIELDS));
    }
}
