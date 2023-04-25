<?php

namespace App\Identity\Application\User;

use App\Identity\Domain\User\User;

interface AuthenticatedUser
{
    public function user(): User;
}
