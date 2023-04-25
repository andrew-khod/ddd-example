<?php

namespace App\Initiative\Infrastructure\Initiative\Persistence\Doctrine;

use App\Initiative\Domain\Initiative\InitiativeId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Symfony\Bridge\Doctrine\Types\UuidType as SymfonyUuidType;
use Symfony\Component\Uid\Uuid;

final class InitiativeIdType extends Type
{
    public function getName()
    {
        return InitiativeId::class;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?InitiativeId
    {
        if (!$value) {
            return $value;
        }

        $uuid = (new SymfonyUuidType())->convertToPHPValue($value, $platform);

        return new InitiativeId($uuid->toRfc4122());
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if (!$value) {
            return null;
        }

        if (is_string($value)) {
            $value = new InitiativeId($value);
        }

        return (new SymfonyUuidType())->convertToDatabaseValue($value->value(), $platform);
//        return (new SymfonyUuidType())->convertToDatabaseValue($value ? $value->value() : null, $platform);
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
