<?php

declare(strict_types=1);

namespace App\Identity\Application\Customer\UseCase\UpdateCustomerProfile;

class CustomerProfileModification
{
    public const USERNAME = 'username';
    public const BIRTHDAY = 'birthday';
    public const POSTAL = 'postal';
    public const GENDER = 'gender';
    public const PHOTO = 'photo';
    public const NEW_PASSWORD = 'new_password';

    private const FIELDS = [
        self::USERNAME,
        self::BIRTHDAY,
        self::POSTAL,
        self::GENDER,
        self::PHOTO,
        self::NEW_PASSWORD,
    ];

    public static function isAllowedField(string $field): bool
    {
        return in_array($field, self::FIELDS);
    }
}
