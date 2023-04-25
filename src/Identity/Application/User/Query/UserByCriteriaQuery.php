<?php

namespace App\Identity\Application\User\Query;

use App\Identity\Domain\User\User;
use App\Identity\Domain\User\UserCollection;

interface UserByCriteriaQuery
{
    public function queryOne(UserCriteria $criteria): ?User;
    public function queryOneV2(UserCriteria $criteria): ?User;

    public function queryMultiple(UserCriteria $criteria): UserCollection;
    public function queryMultipleV2($criteria): UserCollection;
}
