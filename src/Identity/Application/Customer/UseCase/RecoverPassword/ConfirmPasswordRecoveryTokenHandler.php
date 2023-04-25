<?php

declare(strict_types=1);

namespace App\Identity\Application\Customer\UseCase\RecoverPassword;

use App\Identity\Application\Customer\CustomerEntityManager;
use App\Identity\Application\Customer\Query\CustomerByCriteriaQuery;
use App\Identity\Application\Customer\Query\CustomerByPasswordRecoveryTokenCriteria;
use App\Identity\Application\Security\Security;
use App\Identity\Application\User\Exception\UserException;
use App\Identity\Application\User\UseCase\RecoverPassword\ConfirmPasswordRecoveryTokenCommand;
use App\Identity\Application\User\UseCase\RecoverPassword\UserPasswordChanged;
use App\Shared\Application\MessageBus;
use App\Shared\Domain\ActiveLanguage;

final class ConfirmPasswordRecoveryTokenHandler
{
    private CustomerByCriteriaQuery $customerByCriteriaQuery;

    private CustomerEntityManager $customerEntityManager;

    private MessageBus $messageBus;

    private Security $security;
    private ActiveLanguage $activeLanguage;

    public function __construct(CustomerByCriteriaQuery $customerByCriteriaQuery,
                                CustomerEntityManager $customerEntityManager,
                                MessageBus $messageBus,
                                ActiveLanguage $activeLanguage,
                                Security $security)
    {
        $this->customerByCriteriaQuery = $customerByCriteriaQuery;
        $this->customerEntityManager = $customerEntityManager;
        $this->messageBus = $messageBus;
        $this->security = $security;
        $this->activeLanguage = $activeLanguage;
    }

    public function handle(ConfirmPasswordRecoveryTokenCommand $command): void
    {
        $token = $command->token();

        $criteria = new CustomerByPasswordRecoveryTokenCriteria($token);
        $customer = $this->customerByCriteriaQuery->queryOne($criteria);

        if (!$customer) {
            throw UserException::passwordRecoveryTokenNotExist();
        }

        $this->security->changeUserPassword($customer, $command->password());

        $this->customerEntityManager->update();
        $this->messageBus->dispatch(new UserPasswordChanged($customer, $this->activeLanguage));
    }
}
