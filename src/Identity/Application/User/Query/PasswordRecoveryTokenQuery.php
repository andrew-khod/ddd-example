<?php

namespace App\Identity\Application\User\Query;

use App\Identity\Domain\PasswordRecoveryToken\PasswordRecoveryToken;
use App\Identity\Domain\User\BaseUser;
use App\Identity\Domain\User\UserId;

interface PasswordRecoveryTokenQuery
{
    public function byUser(BaseUser $user): ?PasswordRecoveryToken;

    public function byToken(string $token): ?PasswordRecoveryToken;

    public function byUserId(UserId $id): ?PasswordRecoveryToken;
}
