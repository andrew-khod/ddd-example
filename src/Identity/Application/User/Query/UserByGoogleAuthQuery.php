<?php

namespace App\Identity\Application\User\Query;

use App\Identity\Domain\User\User;

interface UserByGoogleAuthQuery
{
    public function query(string $oneTimeCode): ?User;
}
