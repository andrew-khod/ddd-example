<?php

namespace App\Identity\Infrastructure\User\Security\UserCreated;

use App\Identity\Application\User\UseCase\CreateUser\UserCreated;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class UserCreatedHandler implements MessageHandlerInterface
{
    private UserCreatedEmailSender $userCreatedEmailSender;

    public function __construct(UserCreatedEmailSender $userCreatedEmailSender)
    {
        $this->userCreatedEmailSender = $userCreatedEmailSender;
    }

    public function __invoke(UserCreated $message)
    {
        $this->userCreatedEmailSender->send($message);
    }
}