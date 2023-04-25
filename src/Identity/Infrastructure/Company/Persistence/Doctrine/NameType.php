<?php

namespace App\Identity\Infrastructure\Company\Persistence\Doctrine;

use App\Identity\Domain\Company\Name;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

final class NameType extends StringType
{
    public function getName()
    {
        return Name::class;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): Name
    {
        return new Name($value);
    }
}
