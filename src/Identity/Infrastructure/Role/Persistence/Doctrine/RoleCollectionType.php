<?php

declare(strict_types=1);

namespace App\Identity\Infrastructure\Role\Persistence\Doctrine;

use App\Identity\Domain\Role\RoleCollection;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\JsonType;

final class RoleCollectionType extends JsonType
{
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return parent::convertToDatabaseValue($value->toArray(), $platform);
    }

    public function getName()
    {
        return RoleCollection::class;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): RoleCollection
    {
        $roles = parent::convertToPHPValue($value, $platform);

        return new RoleCollection($roles);
    }
}
