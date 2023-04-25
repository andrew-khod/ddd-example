<?php

declare(strict_types=1);

namespace App\Identity\Application\User\UseCase\RecoverPassword;

final class RecoverPasswordCommand
{
    private string $email;

    public function __construct(string $email)
    {
        $this->email = $email;
    }

    public function email(): string
    {
        return $this->email;
    }
}
