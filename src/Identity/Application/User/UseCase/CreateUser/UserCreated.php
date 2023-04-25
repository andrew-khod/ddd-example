<?php

namespace App\Identity\Application\User\UseCase\CreateUser;

use App\Identity\Domain\Company\Company;
use App\Identity\Domain\PasswordRecoveryToken\PasswordRecoveryToken;
use App\Identity\Domain\User\User;
use App\Shared\Application\AsyncMessage;

class UserCreated implements AsyncMessage
{
    private User $user;
    private PasswordRecoveryToken $token;
    private Company $company;

    public function __construct(User $user, PasswordRecoveryToken $token, Company $company)
    {
        $this->user = $user;
        $this->token = $token;
        $this->company = $company;
    }

    public function user(): User
    {
        return $this->user;
    }

    public function token(): PasswordRecoveryToken
    {
        return $this->token;
    }

    public function company(): Company
    {
        return $this->company;
    }
}