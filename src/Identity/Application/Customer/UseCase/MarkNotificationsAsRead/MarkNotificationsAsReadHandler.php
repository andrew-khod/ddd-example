<?php

declare(strict_types=1);

namespace App\Identity\Application\Customer\UseCase\MarkNotificationsAsRead;

use App\Identity\Application\Customer\Query\CustomerNotificationsQuery;
use App\Identity\Application\User\AuthenticatedCustomer;
use App\Initiative\Application\Event\EventReadEntityManager;
use App\Initiative\Domain\Event\EventReadStatus;

final class MarkNotificationsAsReadHandler
{
    private CustomerNotificationsQuery $customerNotificationsQuery;
    private AuthenticatedCustomer $authenticatedCustomer;
    private EventReadEntityManager $eventReadEntityManager;

    public function __construct(AuthenticatedCustomer      $authenticatedCustomer,
                                EventReadEntityManager $eventReadEntityManager,
                                CustomerNotificationsQuery $customerNotificationsQuery
    )
    {
        $this->customerNotificationsQuery = $customerNotificationsQuery;
        $this->authenticatedCustomer = $authenticatedCustomer;
        $this->eventReadEntityManager = $eventReadEntityManager;
    }

    public function handle(): void
    {
        $customer = $this->authenticatedCustomer->user();
        $notifications = $this->customerNotificationsQuery->query($customer);

        foreach ($notifications as $event) {
            if (!$customer->hasReadNotification($event)) {
                $this->eventReadEntityManager->create(new EventReadStatus($event, $customer));
            }
        }

        $this->eventReadEntityManager->persist();
    }
}
