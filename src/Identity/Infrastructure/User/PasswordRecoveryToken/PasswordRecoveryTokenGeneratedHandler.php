<?php

namespace App\Identity\Infrastructure\User\PasswordRecoveryToken;

use App\Identity\Application\User\UseCase\RecoverPassword\PasswordRecoveryTokenGenerated;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class PasswordRecoveryTokenGeneratedHandler implements MessageHandlerInterface
{
    private PasswordRecoveryTokenEmailSender $passwordRecoveryTokenEmailSender;

    public function __construct(PasswordRecoveryTokenEmailSender $passwordRecoveryTokenEmailSender)
    {
        $this->passwordRecoveryTokenEmailSender = $passwordRecoveryTokenEmailSender;
    }

    public function __invoke(PasswordRecoveryTokenGenerated $message)
    {
        $this->passwordRecoveryTokenEmailSender->send($message);
    }
}
