<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure;

use App\Shared\Application\MessageBus as MessageBusInterface;
use Symfony\Component\Messenger\MessageBusInterface as MessageBusImplementation;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

final class MessageBus implements MessageBusInterface
{
    private MessageBusImplementation $messageBus;
    private EventDispatcherInterface $dispatcher;

    public function __construct(MessageBusImplementation $messageBus, EventDispatcherInterface $dispatcher)
    {
        $this->messageBus = $messageBus;
        $this->dispatcher = $dispatcher;
    }

    public function dispatch(object $event)
    {
        $this->dispatcher->dispatch($event);
        $this->messageBus->dispatch($event);
    }
}
