<?php

declare(strict_types=1);

namespace App\Identity\Application\User\UseCase\ResendUserActivation;

final class ResendUserActivationCommand
{
    private string $user;

    public function __construct(string $user)
    {
        $this->user = $user;
    }

    public function user(): string
    {
        return $this->user;
    }
}
