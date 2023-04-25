<?php

declare(strict_types=1);

namespace App\Identity\Application\Customer\UseCase\SignUpCustomer;

use App\Identity\Application\Customer\CustomerEntityManager;
use App\Identity\Application\Customer\Query\CustomerByActivationTokenCriteria;
use App\Identity\Application\Customer\Query\NotActivatedCustomerByCriteriaQuery;
use App\Identity\Application\Security\PasswordEncoder;
use App\Identity\Application\User\Exception\UserException;
use App\Identity\Domain\Customer\BaseCustomer;
use App\Identity\Domain\Customer\Customer;
use App\Identity\Domain\Customer\Username;
use App\Identity\Domain\User\Password;

final class ActivateCustomerHandler
{
    private PasswordEncoder $passwordEncoder;

    private CustomerEntityManager $customerEntityManager;

    private NotActivatedCustomerByCriteriaQuery $notActivatedCustomerByCriteriaQuery;

    public function __construct(PasswordEncoder $passwordEncoder,
                                CustomerEntityManager $customerEntityManagerByActiveTenant,
                                NotActivatedCustomerByCriteriaQuery $notActivatedCustomerByCriteriaQuery)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->notActivatedCustomerByCriteriaQuery = $notActivatedCustomerByCriteriaQuery;
        $this->customerEntityManager = $customerEntityManagerByActiveTenant;
    }

    public function handle(ActivateCustomerCommand $command): BaseCustomer
    {
        $activationToken = $command->token();
        $username = new Username($command->username());
        $password = $this->passwordEncoder->encodePassword($command->password());
        $password = new Password($password);
        $name = null;

        $criteria = new CustomerByActivationTokenCriteria($activationToken);
        $notActivatedCustomer = $this->notActivatedCustomerByCriteriaQuery->queryOne($criteria);

        if (!$notActivatedCustomer) {
            throw UserException::userNotExist();
        }

        $notActivatedCustomer->activate();

        $customer = new Customer(
            $notActivatedCustomer->id(),
            $notActivatedCustomer->email(),
            $password,
            $username,
            $name
        );

        $this->customerEntityManager->sync($customer);
        $this->customerEntityManager->update();
        $this->customerEntityManager->updateInheritanceType($customer, Customer::TYPE);

        return $customer;
    }
}
