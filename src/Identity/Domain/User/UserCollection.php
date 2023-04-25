<?php

declare(strict_types=1);

namespace App\Identity\Domain\User;

use App\Shared\Domain\BaseCollection;

final class UserCollection extends BaseCollection
{
    public function __construct(User ...$users)
    {
        parent::__construct(...$users);
    }
}
