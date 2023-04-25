<?php

declare(strict_types=1);

namespace App\Identity\Domain\User;

final class Email
{
    private string $email;

    public function __construct(string $email)
    {
        $this->email = $email;
    }

    public function value(): string
    {
        return $this->email;
    }

    public function __toString(): string
    {
        return $this->email;
    }
}
