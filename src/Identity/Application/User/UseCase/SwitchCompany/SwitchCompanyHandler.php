<?php

declare(strict_types=1);

namespace App\Identity\Application\User\UseCase\SwitchCompany;

use App\Identity\Application\Company\Query\CompanyByCriteriaQuery;
use App\Identity\Application\Company\Query\CompanyByIdCriteria;
use App\Identity\Application\User\AuthenticatedUser;
use App\Identity\Application\User\Query\UserByCriteriaQuery;
use App\Identity\Application\User\UserEntityManager;
use App\Identity\Domain\Company\CompanyId;
use App\Identity\Domain\User\BaseUser;

final class SwitchCompanyHandler
{
    private UserByCriteriaQuery $userByCriteriaQuery;
    private UserEntityManager $userEntityManager;
//    private UserCompanyByCriteriaQuery $userCompanyByCriteriaQuery;
    private AuthenticatedUser $authenticatedUser;
    private CompanyByCriteriaQuery $companyByCriteriaQuery;

    public function __construct(CompanyByCriteriaQuery $companyByCriteriaQuery,
//                                UserCompanyByCriteriaQuery $userCompanyByCriteriaQuery,
                                AuthenticatedUser $authenticatedUser,
                                UserByCriteriaQuery       $userByCriteriaQuery,
                                UserEntityManager         $userEntityManager,
    )
    {
        $this->userByCriteriaQuery = $userByCriteriaQuery;
        $this->userEntityManager = $userEntityManager;
//        $this->userCompanyByCriteriaQuery = $userCompanyByCriteriaQuery;
        $this->authenticatedUser = $authenticatedUser;
        $this->companyByCriteriaQuery = $companyByCriteriaQuery;
    }

    public function handle(SwitchCompanyCommand $command): BaseUser
    {
        $criteria = new CompanyByIdCriteria(new CompanyId($command->company()));
        $company = $this->companyByCriteriaQuery->queryOne($criteria);
//        $company = $this->userCompanyByCriteriaQuery->queryOne($criteria);
        $user = $this->authenticatedUser->user();

//        $criteria = new UserByIdCriteria(new UserId($command->user()));
//        $user = $this->userByCriteriaQuery->queryOne($criteria);

        // todo if superadmin then switch, if simple admin check if assigned to company
        if ($user->isSuperAdmin() || $user->isAssignedToCompany($company)) {
            $user->switchCompany($company);
        }
//        $user->switchCompany($company);

        $this->userEntityManager->update();

        return $user;
    }
}
