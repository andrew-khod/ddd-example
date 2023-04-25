<?php

declare(strict_types=1);

namespace App\Initiative\Domain\Initiative;

final class InitiativeTranslation
{
    public const NO_REQUESTED_CATEGORIES_EXIST = 'No requested categories exist';
    public const INVALID_FIELD = 'Invalid field';
    public const INITIATIVE_NOT_EXIST = 'Initiative not exist';
    public const COMMENT_NOT_EXIST = 'Comment not exist';
    public const CUSTOMER_ALREADY_JOINED = 'Customer already joined';
    public const NOT_INITIATIVE_OWNER = 'Not initiative owner';
    public const JOIN_TO_EXPIRED = 'Can\'t join to expired';
    public const INITIATIVE_HAVE_NO_ASSOCIATED_LOCATION = 'Initiative have no associated location';
    public const START_DATE_IS_GREATER_THAN_END_DATA = 'Start date is greater than end date';
}