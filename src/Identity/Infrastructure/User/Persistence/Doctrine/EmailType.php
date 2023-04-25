<?php

namespace App\Identity\Infrastructure\User\Persistence\Doctrine;

use App\Identity\Domain\User\Email;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

final class EmailType extends StringType
{
    public function getName()
    {
        return Email::class;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?Email
    {
        if (!$value) {
            return $value;
        }

        return new Email($value);
    }
}
