<?php

declare(strict_types=1);

namespace App\Identity\Domain\Customer;

use DateTime;

class Birthday
{
    private DateTime $date;

    public function __construct(DateTime $date)
    {
        $this->date = $date;
    }

    public function value(): DateTime
    {
        return $this->date;
    }

    public function __toString(): string
    {
        return $this->date->format('Y-m-d H:i:s');
    }
}
