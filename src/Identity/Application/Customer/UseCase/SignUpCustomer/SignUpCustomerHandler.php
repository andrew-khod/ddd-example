<?php

declare(strict_types=1);

namespace App\Identity\Application\Customer\UseCase\SignUpCustomer;

use App\Identity\Application\Customer\CustomerEntityManager;
use App\Identity\Application\Security\Security;
use App\Identity\Domain\Customer\BaseCustomer;
use App\Identity\Domain\Customer\NotActivatedCustomer;
use App\Identity\Domain\User\Email;
use App\Shared\Application\BaseActiveTenant;
use App\Shared\Application\MessageBus;
use App\Shared\Domain\ActiveLanguage;

final class SignUpCustomerHandler
{
    private CustomerEntityManager $customerEntityManager;
    private MessageBus $messageBus;
    private Security $security;
    private ActiveLanguage $activeLanguage;
    private BaseActiveTenant $tenant;

    public function __construct(CustomerEntityManager $customerEntityManager,
                                Security $security,
                                ActiveLanguage $activeLanguage,
                                BaseActiveTenant $tenant,
                                MessageBus $messageBus)
    {
        $this->customerEntityManager = $customerEntityManager;
        $this->messageBus = $messageBus;
        $this->security = $security;
        $this->activeLanguage = $activeLanguage;
        $this->tenant = $tenant;
    }

    public function handle(SignUpCustomerCommand $command): BaseCustomer
    {
        $email = new Email($command->email());
        $customerId = $this->customerEntityManager->nextId();
        $activationToken = $this->security->passwordRecoveryToken()->token();

        $customer = new NotActivatedCustomer($customerId, $email, $activationToken);

        $this->customerEntityManager->create($customer);

        $this->messageBus->dispatch(new NotActivatedCustomerCreated(
            $customer,
            $this->activeLanguage,
            $this->tenant->company()->url()
        ));

        return $customer;
    }
}
