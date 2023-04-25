<?php

declare(strict_types=1);

namespace App\Identity\Domain\Company;

final class Name
{
    private string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function value(): string
    {
        return $this->name;
    }

    public function alias(): string
    {
        $name = strtolower($this->name);
        $name = str_ireplace(' ', '_', $name);

        return sprintf('%s_company', $name);
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
