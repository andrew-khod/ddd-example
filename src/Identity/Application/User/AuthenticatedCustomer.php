<?php

namespace App\Identity\Application\User;

use App\Identity\Domain\Customer\Customer;

//fixme move to Customer submodule
interface AuthenticatedCustomer
{
    public function user(): Customer;
}
