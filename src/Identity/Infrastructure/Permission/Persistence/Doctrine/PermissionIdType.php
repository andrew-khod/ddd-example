<?php

declare(strict_types=1);

namespace App\Identity\Infrastructure\Permission\Persistence\Doctrine;

use App\Identity\Domain\Permission\PermissionId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Symfony\Bridge\Doctrine\Types\UuidType as SymfonyUuidType;
use Symfony\Component\Uid\Uuid;

final class PermissionIdType extends Type
{
    public function getName()
    {
        return PermissionId::class;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?PermissionId
    {
        if (!$value) {
            return null;
        }

        $uuid = (new SymfonyUuidType())->convertToPHPValue($value, $platform);

        return new PermissionId($uuid->toRfc4122());
    }

//    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value instanceof PermissionId) {
            return (new SymfonyUuidType())->convertToDatabaseValue($value->value(), $platform);
        } elseif (is_string($value)) {
            return (new SymfonyUuidType())->convertToDatabaseValue($value, $platform);
//        } else if (is_array($value)) {
//            $items = [];
//
//            foreach ($value as $item) {
//                $items[] = (new SymfonyUuidType)->convertToDatabaseValue($item, $platform);
//            }
//
//            return implode(',', $items);
        }
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return (new SymfonyUuidType())->getSQLDeclaration($column, $platform);
    }

    protected function getUidClass(): string
    {
        return Uuid::class;
    }
}
