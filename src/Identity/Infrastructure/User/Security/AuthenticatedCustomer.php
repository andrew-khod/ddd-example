<?php

declare(strict_types=1);

namespace App\Identity\Infrastructure\User\Security;

use App\Identity\Application\User\AuthenticatedCustomer as AuthenticatedCustomerInterface;
use App\Identity\Application\User\Exception\UserException;
use App\Identity\Domain\Customer\Customer;
use Symfony\Component\Security\Core\Security;

//fixme move to Customer submodule
final class AuthenticatedCustomer implements AuthenticatedCustomerInterface
{
    private Customer $customer;

    public function __construct(Security $security)
    {
        $user = $security->getUser();

        if (!$user) {
            throw UserException::userNotAuthenticated();
        }

        $this->customer = $user;
    }

    public function user(): Customer
    {
        return $this->customer;
    }
}
