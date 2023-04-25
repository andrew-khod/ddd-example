<?php

declare(strict_types=1);

namespace App\Initiative\Application\Initiative\UseCase\JoinInitiative;

use App\Identity\Domain\Customer\CustomerId;
use App\Initiative\Domain\Event\InitiativeEvent;
use App\Initiative\Domain\Initiative\InitiativeId;
use App\Shared\Application\AsyncMessage;
use Symfony\Contracts\EventDispatcher\Event;

final class InitiativeJoined extends Event implements AsyncMessage, InitiativeEvent
{
    private InitiativeId $initiative;
    private CustomerId $customer;

    public function __construct(InitiativeId $initiative, CustomerId $customer)
    {
        $this->initiative = $initiative;
        $this->customer = $customer;
    }

    public static function alias(): string
    {
        return 'initiative_joined';
    }

    public function initiativeId(): InitiativeId
    {
        return $this->initiative;
    }

    public function __serialize(): array
    {
        return [
            'customer' => $this->customer,
        ];
    }
}