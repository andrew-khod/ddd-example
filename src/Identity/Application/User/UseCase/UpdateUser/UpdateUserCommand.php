<?php

declare(strict_types=1);

namespace App\Identity\Application\User\UseCase\UpdateUser;

final class UpdateUserCommand
{
    private array $permissions;
    private string $user;
    private ?string $username;
    private bool $isSuperAdmin;

    public function __construct(string $user,
                                array $permissions,
                                ?string $username = null,
                                bool $isSuperAdmin = false)
    {
        $this->permissions = $permissions;
        $this->user = $user;
        $this->username = $username;
        $this->isSuperAdmin = $isSuperAdmin;
    }

    public function permissions(): array
    {
        return $this->permissions;
    }

    public function user(): string
    {
        return $this->user;
    }

    public function username(): ?string
    {
        return $this->username;
    }

    public function isSuperAdmin(): ?bool
    {
        return $this->isSuperAdmin;
    }
}
