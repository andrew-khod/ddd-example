<?php

declare(strict_types=1);

namespace App\Identity\Application\User\UseCase\CreateUser;

final class CreateUserCommand
{
    private string $email;
    private array $permissions;
    private ?string $username;
    private bool $isSuperAdmin;

    public function __construct(string $email,
                                array $permissions,
                                ?string $username = null,
                                bool $isSuperAdmin = false)
    {
        $this->email = $email;
        $this->permissions = $permissions;
        $this->username = $username;
        $this->isSuperAdmin = $isSuperAdmin;
    }

    public function email(): string
    {
        return $this->email;
    }

    public function permissions(): array
    {
        return $this->permissions;
    }

    public function username(): ?string
    {
        return $this->username;
    }

    public function isSuperAdmin(): bool
    {
        return $this->isSuperAdmin;
    }
}
