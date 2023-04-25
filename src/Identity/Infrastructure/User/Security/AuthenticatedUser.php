<?php

declare(strict_types=1);

namespace App\Identity\Infrastructure\User\Security;

use App\Identity\Application\User\AuthenticatedUser as AuthenticatedUserInterface;
use App\Identity\Application\User\Exception\UserException;
use App\Identity\Domain\User\User;
use Symfony\Component\Security\Core\Security;

final class AuthenticatedUser implements AuthenticatedUserInterface
{
    private User $user;

    public function __construct(Security $security)
    {
        $user = $security->getUser();

        if (!$user) {
            throw UserException::userNotAuthenticated();
        }

        $this->user = $user;
    }

    public function user(): User
    {
        return $this->user;
    }
}
