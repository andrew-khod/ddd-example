<?php

declare(strict_types=1);

namespace App\Identity\Application\Customer\UseCase\HideAllNotifications;

use App\Identity\Application\Customer\CustomerEntityManager;
use App\Identity\Application\User\AuthenticatedCustomer;
use App\Initiative\Application\Event\EventReadEntityManager;
use App\Initiative\Domain\Event\EventReadStatus;

final class HideAllNotificationsHandler
{
    private AuthenticatedCustomer $authenticatedCustomer;
//    private EventReadEntityManager $eventReadEntityManager;
    private CustomerEntityManager $customerEntityManager;

    public function __construct(AuthenticatedCustomer      $authenticatedCustomer,
                                CustomerEntityManager $customerEntityManager
//                                EventReadEntityManager $eventReadEntityManager
    )
    {
        $this->authenticatedCustomer = $authenticatedCustomer;
//        $this->eventReadEntityManager = $eventReadEntityManager;
        $this->customerEntityManager = $customerEntityManager;
    }

    public function handle(): void
    {
        $customer = $this->authenticatedCustomer->user();
        $customer->hideAllNotifications();

        $this->customerEntityManager->update();
//        $this->eventReadEntityManager->persist();
    }
}
