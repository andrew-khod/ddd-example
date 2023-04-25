<?php

namespace App\Initiative\Infrastructure\Initiative\Persistence\Doctrine;

use App\Initiative\Domain\Category\CategoryId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Symfony\Bridge\Doctrine\Types\UuidType as SymfonyUuidType;
use Symfony\Component\Uid\Uuid;

final class InitiativeCategoryIdType extends Type
{
    public function getName()
    {
        return CategoryId::class;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?CategoryId
    {
        if (!$value) {
            return null;
        }

        $uuid = (new SymfonyUuidType())->convertToPHPValue($value, $platform);

        return new CategoryId($uuid->toRfc4122());
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        // fixme research why sometimes we get BaseId, and sometime plain string
        return (new SymfonyUuidType())->convertToDatabaseValue(is_string($value) ? $value : $value->value(), $platform);
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform)
    {
        return (new SymfonyUuidType())->getSQLDeclaration($column, $platform);
    }

    protected function getUidClass(): string
    {
        return Uuid::class;
    }
}
