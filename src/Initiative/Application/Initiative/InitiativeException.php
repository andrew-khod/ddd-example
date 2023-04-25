<?php

declare(strict_types=1);

namespace App\Initiative\Application\Initiative;

use App\Initiative\Domain\Initiative\InitiativeTranslation;
use App\Shared\Application\HttpResponseCode;
use App\Shared\Domain\BaseException;
use DomainException;

final class InitiativeException extends DomainException implements BaseException
{
    public static function emptyCategories(): self
    {
        return new self(InitiativeTranslation::NO_REQUESTED_CATEGORIES_EXIST, HttpResponseCode::badRequest());
    }

    public static function invalidField(): self
    {
        return new self(InitiativeTranslation::INVALID_FIELD, HttpResponseCode::badRequest());
    }

    public static function initiativeNotExist(): self
    {
        return new self(InitiativeTranslation::INITIATIVE_NOT_EXIST, HttpResponseCode::notFound());
    }

    public static function customerAlreadyJoined(): self
    {
        return new self(InitiativeTranslation::CUSTOMER_ALREADY_JOINED, HttpResponseCode::conflict());
    }

    public static function notInitiativeOwner(): self
    {
        return new self(InitiativeTranslation::NOT_INITIATIVE_OWNER, HttpResponseCode::badRequest());
    }

    public static function joinToExpired(): self
    {
        return new self(InitiativeTranslation::JOIN_TO_EXPIRED, HttpResponseCode::badRequest());
    }

    public static function noLocationChosenYet(): self
    {
        return new self(InitiativeTranslation::INITIATIVE_HAVE_NO_ASSOCIATED_LOCATION, HttpResponseCode::badRequest());
    }

    public static function invalidFilterField(string $field)
    {
        return new self(sprintf('Invalid filter field: %s', $field), HttpResponseCode::badRequest());
    }

    public static function commentNotExist()
    {
        return new self(InitiativeTranslation::COMMENT_NOT_EXIST, HttpResponseCode::notFound());
    }

    public function getErrors(): array
    {
        return ['InitiativeException' => $this->message];
    }
}
