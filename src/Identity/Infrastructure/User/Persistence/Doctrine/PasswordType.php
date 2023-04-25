<?php

namespace App\Identity\Infrastructure\User\Persistence\Doctrine;

use App\Identity\Domain\User\Password;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

final class PasswordType extends StringType
{
    public function getName()
    {
        return Password::class;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?Password
    {
        return $value ? new Password($value) : null;
    }
}
