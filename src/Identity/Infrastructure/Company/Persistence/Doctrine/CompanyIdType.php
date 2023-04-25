<?php

namespace App\Identity\Infrastructure\Company\Persistence\Doctrine;

use App\Identity\Domain\Company\CompanyId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Symfony\Bridge\Doctrine\Types\UuidType as SymfonyUuidType;
use Symfony\Component\Uid\Uuid;

final class CompanyIdType extends Type
{
    public function getName()
    {
        return CompanyId::class;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?CompanyId
    {
        if (!$value) {
            return null;
        }

        $uuid = (new SymfonyUuidType())->convertToPHPValue($value, $platform);

        return new CompanyId($uuid->toRfc4122());
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
