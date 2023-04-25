<?php

declare(strict_types=1);

namespace App\Identity\Domain\Customer;

final class Username
{
    private string $username;

    public function __construct(string $username)
    {
        $this->username = $username;
    }

    public function value(): string
    {
        return $this->username;
    }

    public function __toString(): string
    {
        return $this->username;
    }
}
