<?php

namespace App\Identity\Application\Customer\Query;

use App\Identity\Application\User\UseCase\LinkGoogleAuth\GoogleUser;
use App\Identity\Domain\Customer\Customer;

interface CustomerByGoogleAuthQuery
{
    public function queryByOneTimeCode(string $oneTimeCode): ?Customer;

    public function queryByGoogleUser(GoogleUser $user): ?Customer;
}
