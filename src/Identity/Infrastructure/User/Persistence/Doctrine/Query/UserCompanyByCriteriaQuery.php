<?php

declare(strict_types=1);

namespace App\Identity\Infrastructure\User\Persistence\Doctrine\Query;

use App\Identity\Application\User\Query\UserCriteria;
use App\Identity\Application\UserCompany\Query\UserCompanyByCriteriaQuery as UserCompanyByCriteriaQueryInterface;
use App\Identity\Domain\UserCompany\UserCompany;
use App\Shared\Infrastructure\Doctrine\Query\BaseQuery;

final class UserCompanyByCriteriaQuery extends BaseQuery implements UserCompanyByCriteriaQueryInterface
{
    protected function getClass(): string
    {
        return UserCompany::class;
    }

    public function queryOne(UserCriteria $criteria): ?UserCompany
    {
        return $this->findOneByCriteria($criteria);
    }
}
