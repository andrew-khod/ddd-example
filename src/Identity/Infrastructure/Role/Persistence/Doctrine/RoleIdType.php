<?php

declare(strict_types=1);

namespace App\Identity\Infrastructure\Role\Persistence\Doctrine;

use App\Identity\Domain\Role\RoleId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Symfony\Bridge\Doctrine\Types\UuidType as SymfonyUuidType;
use Symfony\Component\Uid\Uuid;

final class RoleIdType extends Type
{
    public function getName()
    {
        return RoleId::class;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?RoleId
    {
        $uuid = (new SymfonyUuidType())->convertToPHPValue($value, $platform);

        return new RoleId($uuid->toRfc4122());
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        return (new SymfonyUuidType())->convertToDatabaseValue($value->value(), $platform);
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
