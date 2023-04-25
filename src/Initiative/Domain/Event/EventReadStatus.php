<?php

declare(strict_types=1);

namespace App\Initiative\Domain\Event;

use App\Identity\Domain\Customer\AbstractCustomer;
use App\Identity\Domain\Customer\Customer;
use Symfony\Component\Uid\Uuid;

//class Event implements Entity
class EventReadStatus
{
    private Uuid $id;
    private Event $event;
    private AbstractCustomer $customer;
    private bool $is_hidden = false;

    public function __construct(Event $event, Customer $customer)
    {
        $this->id = Uuid::v4();
        $this->event = $event;
        $this->customer = $customer;
    }

    public function id(): Uuid
    {
        return $this->id;
    }

    public function event(): Event
    {
        return $this->event;
    }

    public function customer(): AbstractCustomer
    {
        return $this->customer;
    }

    public function hide(): void
    {
        $this->is_hidden = true;
    }
}
