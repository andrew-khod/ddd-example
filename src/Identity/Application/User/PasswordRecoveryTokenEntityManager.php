<?php

namespace App\Identity\Application\User;

use App\Identity\Domain\PasswordRecoveryToken\PasswordRecoveryToken;

interface PasswordRecoveryTokenEntityManager
{
    public function save(PasswordRecoveryToken $passwordRecoveryToken): void;

    public function delete(PasswordRecoveryToken $token): void;
}
