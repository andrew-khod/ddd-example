<?php

declare(strict_types=1);

namespace App\Identity\Application\User\UseCase\RecoverPassword;

final class ConfirmPasswordRecoveryTokenCommand
{
    private string $token;
    private string $password;

    public function __construct(string $token, string $password)
    {
        $this->token = $token;
        $this->password = $password;
    }

    public function token(): string
    {
        return $this->token;
    }

    public function password(): string
    {
        return $this->password;
    }
}
