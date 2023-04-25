<?php

declare(strict_types=1);

namespace App\Identity\Infrastructure\User\Persistence\Doctrine\Query;

use App\Identity\Application\User\Query\UserByCriteriaQuery as UserByCriteriaQueryInterface;
use App\Identity\Application\User\Query\UserCriteria;
use App\Identity\Domain\User\User;
use App\Identity\Domain\User\UserCollection;
use App\Shared\Infrastructure\Doctrine\Query\BaseQuery;

//final class UserByCriteriaQuery extends SwitchableTenantBaseQuery implements UserByCriteriaQueryInterface
final class UserByCriteriaQuery extends BaseQuery implements UserByCriteriaQueryInterface
{
    protected array $filters = [
        'softdeleteable' => true,
    ];

    protected function getClass(): string
    {
        return User::class;
    }

    public function queryOne(UserCriteria $criteria): ?User
    {
        return $this->findOneByCriteria($criteria);
    }

    public function queryOneV2(UserCriteria $criteria): ?User
    {
        return $this->findOneByCriteriaV2($criteria);
    }

    public function queryMultiple(UserCriteria $criteria): UserCollection
    {
        return new UserCollection(...$this->findMultipleByCriteria($criteria));
    }

    public function queryMultipleV2($criteria): UserCollection
    {
        return new UserCollection(...$this->findMultipleByCriteriaV2($criteria));
    }
}
