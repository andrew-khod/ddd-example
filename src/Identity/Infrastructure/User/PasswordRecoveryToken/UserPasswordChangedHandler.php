<?php

namespace App\Identity\Infrastructure\User\PasswordRecoveryToken;

use App\Identity\Application\User\UseCase\RecoverPassword\UserPasswordChanged;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class UserPasswordChangedHandler implements MessageHandlerInterface
{
    private UserPasswordChangedEmailSender $userPasswordChangedEmailSender;

    public function __construct(UserPasswordChangedEmailSender $userPasswordChangedEmailSender)
    {
        $this->userPasswordChangedEmailSender = $userPasswordChangedEmailSender;
    }

    public function __invoke(UserPasswordChanged $message)
    {
        $this->userPasswordChangedEmailSender->send($message);
    }
}
