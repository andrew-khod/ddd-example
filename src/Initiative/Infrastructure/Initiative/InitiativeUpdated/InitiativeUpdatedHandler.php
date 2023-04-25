<?php

namespace App\Initiative\Infrastructure\Initiative\InitiativeUpdated;

use App\Initiative\Application\Initiative\UseCase\UpdateInitiative\InitiativeUpdated;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class InitiativeUpdatedHandler implements MessageHandlerInterface
{
    private InitiativeUpdatedEmailSender $initiativeUpdatedEmailSender;

    public function __construct(InitiativeUpdatedEmailSender $initiativeUpdatedEmailSender)
    {
        $this->initiativeUpdatedEmailSender = $initiativeUpdatedEmailSender;
    }

    public function __invoke(InitiativeUpdated $message)
    {
        $this->initiativeUpdatedEmailSender->send($message);
    }
}