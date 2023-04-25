<?php

namespace App\Identity\Infrastructure\Customer\Persistence\Doctrine;

use App\Identity\Domain\Customer\Username;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

final class CustomerUserNameType extends StringType
{
    public function getName()
    {
        return Username::class;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?Username
    {
        return $value ? new Username($value) : null;
    }
}
