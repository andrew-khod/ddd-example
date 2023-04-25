<?php

declare(strict_types=1);

namespace App\Identity\Infrastructure\User\Password;

use App\Identity\Application\Security\PasswordGenerator as PasswordGeneratorInterface;

final class RandomPasswordGenerator implements PasswordGeneratorInterface
{
    public function generate(): string
    {
        $hash = sha1(random_bytes(8));

        return substr($hash, 0, 6);
    }
}
