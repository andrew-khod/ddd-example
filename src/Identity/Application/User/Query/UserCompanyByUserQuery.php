<?php

namespace App\Identity\Application\User\Query;

use App\Identity\Domain\Company\Company;
use App\Identity\Domain\User\BaseUser;

interface UserCompanyByUserQuery
{
    public function query(BaseUser $user): ?Company;
}
