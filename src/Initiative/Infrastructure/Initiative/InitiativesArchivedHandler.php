<?php

declare(strict_types=1);

namespace App\Initiative\Infrastructure\Initiative;

use App\Identity\Application\Customer\UseCase\DeleteCustomer\InitiativesArchived;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class InitiativesArchivedHandler implements MessageHandlerInterface
{
    private InitiativesArchivedEmailSender $sender;

    public function __construct(InitiativesArchivedEmailSender $sender)
    {
        $this->sender = $sender;
    }

    public function __invoke(InitiativesArchived $message)
    {
        $this->sender->send($message);
    }
}