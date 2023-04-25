<?php

declare(strict_types=1);

namespace App\Identity\Domain\User;

class UserTranslation
{
    public const INVALID_OR_EXPIRED_ONE_TIME_CODE = 'Invalid or expired one time code';
    public const USER_ALREADY_EXIST = 'User already exist';
    public const USERNAME_ALREADY_EXIST = 'Username already exist';
    public const USER_NOT_EXIST = 'User not exist';
    public const USER_NOT_AUTHENTICATED = 'User not authenticated';
    public const AUTH_VENDOR_NOT_ALLOWED = 'Auth vendor not allowed';
    public const AUTH_VENDOR_ALREADY_EXIST = 'Auth vendor already exist';
    public const PASSWORD_RECOVERY_TOKEN_NOT_EXIST = 'Password recovery token not exist';
    public const AUTH_VENDOR_ALLOWED = 'Authentication vendor allowed';
    public const BANNED = 'You was banned';
}