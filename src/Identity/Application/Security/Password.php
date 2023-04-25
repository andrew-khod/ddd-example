<?php

declare(strict_types=1);

namespace App\Identity\Application\Security;

final class Password
{
    private string $raw;
    private string $hash;

    public function __construct(string $raw, string $hash)
    {
        $this->raw = $raw;
        $this->hash = $hash;
    }

    public function hash(): string
    {
        return $this->hash;
    }

    public function raw(): string
    {
        return $this->raw;
    }
}
