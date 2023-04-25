<?php

declare(strict_types=1);

namespace App\Identity\Infrastructure\User\Persistence\Doctrine\Query;

use App\Identity\Application\User\Query\UserCompanyByUserQuery as UserCompanyByEmailQueryInterface;
use App\Identity\Domain\Company\Company;
use App\Identity\Domain\User\BaseUser;
use App\Identity\Domain\UserCompany\UserCompany;
use App\Shared\Infrastructure\Doctrine\Query\BaseQuery;

final class UserCompanyByUserQuery extends BaseQuery implements UserCompanyByEmailQueryInterface
{
    protected function getClass(): string
    {
        return UserCompany::class;
    }

    public function query(BaseUser $user): ?Company
    {
        $userCompany = $this->repository->findOneBy([
            'user' => $user,
        ]);

        return $userCompany->company();
    }
}
