<?php

declare(strict_types=1);

namespace App\Identity\UI\Dashboard\Security\Http\Controller;

use App\Identity\Domain\User\User;

final class UserPayload
{
    private User $user;
    private array $languages;

    public function __construct(User $user, array $languages)
    {
        $this->user = $user;
        $this->languages = $languages;
    }

    public function user(): User
    {
        return $this->user;
    }

    public function languages(): array
    {
        return $this->languages;
    }
}