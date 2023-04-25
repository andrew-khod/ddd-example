<?php

namespace App\Initiative\Infrastructure\Image;

use App\Initiative\Domain\Image\ImageId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Symfony\Bridge\Doctrine\Types\UuidType as SymfonyUuidType;
use Symfony\Component\Uid\Uuid;

final class ImageIdType extends Type
{
    public function getName()
    {
        return ImageId::class;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?ImageId
    {
        if (!$value) {
            return $value;
        }

        $uuid = (new SymfonyUuidType())->convertToPHPValue($value, $platform);

        return new ImageId($uuid->toRfc4122());
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
