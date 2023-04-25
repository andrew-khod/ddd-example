<?php

declare(strict_types=1);

namespace App\Identity\Application\Customer\UseCase\CreateCustomer;

use App\Identity\Application\Customer\CustomerEntityManager;
use App\Identity\Application\Security\PasswordEncoder;
use App\Identity\Domain\Customer\Customer;
use App\Identity\Domain\Customer\Name;
use App\Identity\Domain\Customer\Username;
use App\Identity\Domain\User\Email;
use App\Identity\Domain\User\Password;

final class CreateCustomerHandler
{
    private CustomerEntityManager $customerEntityManager;

    private PasswordEncoder $passwordEncoder;

    public function __construct(CustomerEntityManager $customerEntityManagerByActiveTenant,
                                PasswordEncoder $passwordEncoder)
    {
        $this->customerEntityManager = $customerEntityManagerByActiveTenant;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function handle(CreateCustomerCommand $command): Customer
    {
        $customerId = $this->customerEntityManager->nextId();
        $email = new Email($command->email());
        $username = new Username($command->username());
        $name = new Name($command->firstname(), $command->lastname());
        $password = $this->passwordEncoder->encodePassword($command->password());
        $password = new Password($password);

        $customer = new Customer(
            $customerId,
            $email,
            $password,
            $username,
            $name,
        );

        $this->customerEntityManager->create($customer);

        return $customer;
    }
}
