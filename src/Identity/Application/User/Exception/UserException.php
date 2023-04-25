<?php

declare(strict_types=1);

namespace App\Identity\Application\User\Exception;

use App\Identity\Domain\User\UserTranslation;
use App\Shared\Application\HttpResponseCode;
use App\Shared\Domain\BaseException;
use DomainException;

final class UserException extends DomainException implements BaseException
{
    public static function userAlreadyExist(): self
    {
        return new self(UserTranslation::USER_ALREADY_EXIST, HttpResponseCode::conflict());
    }

    public static function usernameAlreadyExist(): self
    {
        return new self(UserTranslation::USERNAME_ALREADY_EXIST, HttpResponseCode::conflict());
    }

    public static function userNotExist(): self
    {
        return new self(UserTranslation::USER_NOT_EXIST, HttpResponseCode::notFound());
    }

    public static function userNotAuthenticated(): self
    {
        // what about to throw the forbidden code instead of unauthorized one, in the reason that authenticated != unauthorized?
        return new self(UserTranslation::USER_NOT_AUTHENTICATED, HttpResponseCode::unauthorized());
    }

    public static function authVendorNotAllowed(): self
    {
        return new self(UserTranslation::AUTH_VENDOR_NOT_ALLOWED, HttpResponseCode::forbidden());
    }

    public static function authVendorAlreadyExist(): self
    {
        return new self(UserTranslation::AUTH_VENDOR_ALREADY_EXIST, HttpResponseCode::conflict());
    }

    public static function passwordRecoveryTokenNotExist(): self
    {
        return new self(UserTranslation::PASSWORD_RECOVERY_TOKEN_NOT_EXIST, HttpResponseCode::notFound());
    }

    public static function banned(): self
    {
        return new self(UserTranslation::BANNED, HttpResponseCode::forbidden());
    }

    public function getErrors(): array
    {
        return ['UserException' => $this->message];
    }
}
