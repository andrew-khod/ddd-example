<?php

declare(strict_types=1);

namespace App\Identity\Domain\Customer;

class Postal
{
    private string $code;

    public function __construct(string $code)
    {
        $this->code = $code;
    }

    public function __toString(): string
    {
        return $this->code;
    }

    public function value(): string
    {
        return $this->code;
    }
}
