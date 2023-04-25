<?php

namespace App\Identity\Application\UserCompany\Query;

use App\Identity\Application\User\Query\UserCriteria;
use App\Identity\Domain\UserCompany\UserCompany;

interface UserCompanyByCriteriaQuery
{
    public function queryOne(UserCriteria $criteria): ?UserCompany;
}