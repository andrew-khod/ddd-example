<?php

declare(strict_types=1);

namespace App\Identity\Application\Customer\UseCase\SignUpCustomer;

final class SignUpCustomerCommand
{
    private string $email;

    public function __construct(string $email)
    {
        $this->email = $email;
    }

    public function email(): string
    {
        return $this->email;
    }
}
