<?php

namespace App\Initiative\Infrastructure\Initiative\Persistence\Doctrine;

use App\Initiative\Domain\Comment\CommentId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Symfony\Bridge\Doctrine\Types\UuidType as SymfonyUuidType;
use Symfony\Component\Uid\Uuid;

final class CommentIdType extends Type
{
    public function getName()
    {
        return CommentId::class;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?CommentId
    {
        if (!$value) {
            return $value;
        }

        $uuid = (new SymfonyUuidType())->convertToPHPValue($value, $platform);

        return new CommentId($uuid->toRfc4122());
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
