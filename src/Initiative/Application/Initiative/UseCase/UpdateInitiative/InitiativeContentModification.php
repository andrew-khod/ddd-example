<?php

declare(strict_types=1);

namespace App\Initiative\Application\Initiative\UseCase\UpdateInitiative;

class InitiativeContentModification
{
    public const TYPE = 'type';
    public const CATEGORY = 'category';
    public const TITLE = 'title';
    public const DESCRIPTION = 'description';
    public const DATE_START = 'date_start';
    public const DATE_END = 'date_end';
    public const MINIMAL_JOINED_PEOPLE = 'minimal_joined_people';
    public const LOCATION = 'location';
    public const LOCATION_NAME = 'location_name';
    public const LOCATION_RADIUS_VALUE = 'location_radius_value';
    public const LOCATION_RADIUS_UNIT = 'location_radius_unit';
    public const IMAGES = 'images';
    public const IMAGES_TO_REMOVE = 'images_to_remove';
    public const QUESTIONNAIRES = 'questionnaires';

    private const FIELDS = [
        self::TYPE,
        self::CATEGORY,
        self::TITLE,
        self::DESCRIPTION,
        self::DATE_START,
        self::DATE_END,
        self::MINIMAL_JOINED_PEOPLE,
        self::LOCATION,
        self::LOCATION_NAME,
        self::LOCATION_RADIUS_VALUE,
        self::LOCATION_RADIUS_UNIT,
        self::IMAGES,
        self::IMAGES_TO_REMOVE,
        self::QUESTIONNAIRES,
    ];

    public static function isAllowedField(string $field): bool
    {
        return in_array($field, self::FIELDS);
    }
}
