<?php

declare(strict_types=1);

namespace App\Initiative\Infrastructure\Initiative;

use App\Initiative\Application\Initiative\UseCase\ArchiveInitiative\InitiativeArchived;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class InitiativeArchivedHandler implements MessageHandlerInterface
{
    private InitiativesArchivedEmailSender $sender;

    public function __construct(InitiativesArchivedEmailSender $sender)
    {
        $this->sender = $sender;
    }

    public function __invoke(InitiativeArchived $message)
    {
        $this->sender->send($message);
    }
}