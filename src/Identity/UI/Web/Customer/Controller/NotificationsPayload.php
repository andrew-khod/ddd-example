<?php

declare(strict_types=1);

namespace App\Identity\UI\Web\Customer\Controller;

final class NotificationsPayload
{
    private array $notifications;

    public function __construct(array $notifications)
    {
        $this->notifications = $notifications;
    }

    public function notifications(): array
    {
        return $this->notifications;
    }
}