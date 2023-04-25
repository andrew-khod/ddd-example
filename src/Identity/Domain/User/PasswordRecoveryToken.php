<?php

declare(strict_types=1);

namespace App\Identity\Domain\User;

final class PasswordRecoveryToken
{
    private string $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    public function token(): string
    {
        return $this->token;
    }

    public function __toString(): string
    {
        return $this->token;
    }
}
