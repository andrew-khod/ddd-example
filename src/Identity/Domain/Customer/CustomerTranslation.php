<?php

declare(strict_types=1);

namespace App\Identity\Domain\Customer;

class CustomerTranslation
{
    public const INVALID_GENDER = 'Invalid gender';
    public const PASSWORD_RECOVERY_TOKEN_SENT = 'Password recovery token sent to the requested email';
    public const PASSWORD_CHANGED = 'Password changed';
    public const ACCOUNT_DELETED = 'Account deleted';
}