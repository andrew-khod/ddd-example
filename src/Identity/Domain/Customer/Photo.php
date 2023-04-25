<?php

declare(strict_types=1);

namespace App\Identity\Domain\Customer;

class Photo
{
    private string $photo;

    public function __construct(string $photo)
    {
        $this->photo = $photo;
    }

    public function value(): string
    {
        return $this->photo;
    }

    public function __toString(): string
    {
        return $this->photo;
    }
}
