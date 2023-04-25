<?php

declare(strict_types=1);

namespace App\Identity\Domain\User;

final class Password
{
    private string $password;

    public function __construct(string $password)
    {
        $this->password = $password;
    }

    public function password(): string
    {
        return $this->password;
    }

    public function __toString(): string
    {
        return $this->password;
    }
}
