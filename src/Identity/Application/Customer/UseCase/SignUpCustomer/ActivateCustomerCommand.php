<?php

declare(strict_types=1);

namespace App\Identity\Application\Customer\UseCase\SignUpCustomer;

final class ActivateCustomerCommand
{
    private string $token;
    private string $password;
    private string $username;

    public function __construct(string $token, string $password, string $username)
    {
        $this->token = $token;
        $this->password = $password;
        $this->username = $username;
    }

    public function token(): string
    {
        return $this->token;
    }

    public function password(): string
    {
        return $this->password;
    }

    public function username(): string
    {
        return $this->username;
    }
}
