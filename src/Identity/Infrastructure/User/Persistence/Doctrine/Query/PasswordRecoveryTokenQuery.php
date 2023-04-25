<?php

declare(strict_types=1);

namespace App\Identity\Infrastructure\User\Persistence\Doctrine\Query;

use App\Identity\Application\User\Query\PasswordRecoveryTokenQuery as PasswordRecoveryTokenByUserQueryInterface;
use App\Identity\Domain\PasswordRecoveryToken\PasswordRecoveryToken;
use App\Identity\Domain\User\BaseUser;
use App\Identity\Domain\User\UserId;
use App\Shared\Infrastructure\Doctrine\Query\BaseQuery;

final class PasswordRecoveryTokenQuery extends BaseQuery implements PasswordRecoveryTokenByUserQueryInterface
{
    protected function getClass(): string
    {
        return PasswordRecoveryToken::class;
    }

    public function byUser(BaseUser $user): ?PasswordRecoveryToken
    {
        return $this->repository->findOneBy([
            'user_id' => $user->id(),
        ]);
    }

    public function byToken(string $token): ?PasswordRecoveryToken
    {
        return $this->repository->findOneBy([
            'token' => $token,
        ]);
    }

    public function byUserId(UserId $id): ?PasswordRecoveryToken
    {
        return $this->repository->findOneBy([
            'user_id' => $id,
        ]);
    }
}
