<?php

namespace App\Identity\Application\User\Query;

class AllUserCriteria implements UserCriteria
{
    public function toArray(): array
    {
        return [];
    }

    public function operatorFor(string $field): ?string
    {
        return null;
    }
}