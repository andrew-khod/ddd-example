<?php

namespace App\Identity\Infrastructure\Customer\Persistence\Doctrine;

use App\Identity\Domain\Customer\CustomerId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Symfony\Bridge\Doctrine\Types\UuidType as SymfonyUuidType;
use Symfony\Component\Uid\Uuid;

final class CustomerIdType extends Type
{
    public function getName()
    {
        return CustomerId::class;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?CustomerId
    {
        if (!$value) {
            return $value;
        }

        $uuid = (new SymfonyUuidType())->convertToPHPValue($value, $platform);

        return new CustomerId($uuid->toRfc4122());
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if (!$value) {
            return null;
        }

        if (is_string($value)) {
            $value = new CustomerId($value);
        }

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
