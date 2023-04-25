<?php

namespace App\Shared\Application;

interface QueryCriteria
{
    public function toArray(): array;

    public function operatorFor(string $field): ?string;
}
