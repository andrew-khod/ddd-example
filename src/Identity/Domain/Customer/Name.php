<?php

declare(strict_types=1);

namespace App\Identity\Domain\Customer;

final class Name
{
    private ?string $firstName;

    private ?string $lastName;

    public function __construct(string $firstName = null, string $lastName = null)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }

    public function firstname(): ?string
    {
        return $this->firstName;
    }

    public function lastname(): ?string
    {
        return $this->lastName;
    }

    public function __toString(): string
    {
        $format = '%s %s';

        if (!$this->firstName && !$this->lastName) {
            return '';
        }

        if (!$this->firstName) {
            return sprintf('%s', $this->lastName);
        }

        if (!$this->lastName) {
            return sprintf('%s', $this->firstName);
        }

        return sprintf('%s %s', $this->firstName, $this->lastName);
    }
}
