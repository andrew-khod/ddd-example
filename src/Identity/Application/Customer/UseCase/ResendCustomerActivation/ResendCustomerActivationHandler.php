<?php

declare(strict_types=1);

namespace App\Identity\Application\Customer\UseCase\ResendCustomerActivation;

use App\Identity\Application\Customer\Query\CustomerByIdCriteria;
use App\Identity\Application\Customer\Query\NotActivatedCustomerByCriteriaQuery;
use App\Identity\Application\Customer\UseCase\SignUpCustomer\NotActivatedCustomerCreated;
use App\Identity\Application\User\Exception\UserException;
use App\Identity\Domain\Customer\CustomerId;
use App\Identity\Infrastructure\Company\SwitchableActiveTenant;
use App\Shared\Application\MessageBus;
use App\Shared\Domain\ActiveLanguage;

final class ResendCustomerActivationHandler
{
    private NotActivatedCustomerByCriteriaQuery $notActivatedCustomerByCriteriaQuery;
    private ActiveLanguage $activeLanguage;
    private SwitchableActiveTenant $tenant;
    private MessageBus $messageBus;

    public function __construct(NotActivatedCustomerByCriteriaQuery $notActivatedCustomerByCriteriaQuery,
                                ActiveLanguage                      $activeLanguage,
                                MessageBus $messageBus,
                                SwitchableActiveTenant              $switchableActiveTenant)
    {
        $this->notActivatedCustomerByCriteriaQuery = $notActivatedCustomerByCriteriaQuery;
        $this->activeLanguage = $activeLanguage;
        $this->tenant = $switchableActiveTenant;
        $this->messageBus = $messageBus;
    }

    public function handle(ResendCustomerActivationCommand $command): void
    {
        $customer = $this->notActivatedCustomerByCriteriaQuery->queryOne(
            new CustomerByIdCriteria(new CustomerId($command->customer()))
        );

        if (!$customer) {
            throw UserException::userNotExist();
        }

        $this->messageBus->dispatch(new NotActivatedCustomerCreated(
            $customer,
            $this->activeLanguage,
            $this->tenant->company()->url()
        ));
    }
}
