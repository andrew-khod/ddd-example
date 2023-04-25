<?php

declare(strict_types=1);

namespace App\Initiative\Domain\Initiative;

final class StatusList
{
    public const PUBLISHED = 'published';
    public const EXPIRED = 'expired';
    public const ARCHIVED = 'archived';

    public function list(): array
    {
        return [
            self::PUBLISHED,
            self::EXPIRED,
            self::ARCHIVED,
        ];
    }
}