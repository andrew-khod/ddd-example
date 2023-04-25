<?php

declare(strict_types=1);

namespace App\Identity\Application\Customer\UseCase\RecoverPassword;

use App\Identity\Application\Customer\CustomerEntityManager;
use App\Identity\Application\Customer\Query\CustomerByCriteriaQuery;
use App\Identity\Application\Customer\Query\CustomerByEmailCriteria;
use App\Identity\Application\Security\Security;
use App\Identity\Application\User\Exception\UserException;
use App\Identity\Application\User\UseCase\RecoverPassword\PasswordRecoveryTokenGenerated;
use App\Identity\Application\User\UseCase\RecoverPassword\RecoverPasswordCommand;
use App\Identity\Domain\PasswordRecoveryToken\PasswordRecoveryToken;
use App\Identity\Domain\User\Email;
use App\Shared\Application\BaseActiveTenant;
use App\Shared\Application\MessageBus;
use App\Shared\Domain\ActiveLanguage;

final class RecoverPasswordHandler
{
    private CustomerByCriteriaQuery $customerByCriteriaQuery;

    private Security $security;

    private MessageBus $messageBus;

    private CustomerEntityManager $customerEntityManager;

    private ActiveLanguage $activeLanguage;
    private BaseActiveTenant $tenant;

    public function __construct(CustomerByCriteriaQuery $customerByCriteriaQuery,
                                CustomerEntityManager $customerEntityManager,
                                Security $security,
                                ActiveLanguage $activeLanguage,
                                BaseActiveTenant $tenant,
                                MessageBus $messageBus)
    {
        $this->customerByCriteriaQuery = $customerByCriteriaQuery;
        $this->security = $security;
        $this->messageBus = $messageBus;
        $this->customerEntityManager = $customerEntityManager;
        $this->activeLanguage = $activeLanguage;
        $this->tenant = $tenant;
    }

    public function handle(RecoverPasswordCommand $command): void
    {
        $email = new Email($command->email());

        $criteria = new CustomerByEmailCriteria($email);
        $customer = $this->customerByCriteriaQuery->queryOne($criteria);

        if (!$customer) {
            throw UserException::userNotExist();
        }

        if ($customer->passwordRecoveryToken()) {
            $this->messageBus->dispatch(
                new PasswordRecoveryTokenGenerated(
                    $customer,
                    $customer->passwordRecoveryToken(),
                    $this->activeLanguage
                )
            );

            return;
        }

        $passwordRecoveryToken = $this->security->passwordRecoveryToken()->token();
        $passwordRecoveryToken = new PasswordRecoveryToken($customer->id(), $passwordRecoveryToken);

        $customer->updatePasswordRecoveryToken($passwordRecoveryToken);
        $this->customerEntityManager->update();
        $this->messageBus->dispatch(
            new PasswordRecoveryTokenGenerated(
                $customer,
                $passwordRecoveryToken,
                $this->activeLanguage,
                $this->tenant->company()->url(),
            )
        );
    }
}
