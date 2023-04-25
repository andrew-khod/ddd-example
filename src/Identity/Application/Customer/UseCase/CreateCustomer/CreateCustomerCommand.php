<?php

declare(strict_types=1);

namespace App\Identity\Application\Customer\UseCase\CreateCustomer;

final class CreateCustomerCommand
{
    private string $email;
    private string $username;
    private string $password;
    private ?string $firstname;
    private ?string $lastname;

    public function __construct(string $email,
                                string $username,
                                string $password,
                                string $firstname = null,
                                string $lastname = null)
    {
        $this->email = $email;
        $this->username = $username;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->password = $password;
    }

    public function email(): string
    {
        return $this->email;
    }

    public function username(): string
    {
        return $this->username;
    }

    public function password(): string
    {
        return $this->password;
    }

    public function firstname(): ?string
    {
        return $this->firstname;
    }

    public function lastname(): ?string
    {
        return $this->lastname;
    }
}
