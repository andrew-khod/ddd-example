<?php

declare(strict_types=1);

namespace App\Initiative\Application\Initiative\UseCase\UpdateInitiative;

use App\Shared\Domain\PreUploadedImage\PreUploadedImageCollection;

final class UpdateInitiativeCommand
{
    private array $fields = [];
    private string $initiative;

    public function __construct(string $initiative, array $fields)
    {
        $this->initiative = $initiative;

        foreach ($fields as $field => $value) {
            if (InitiativeContentModification::isAllowedField($field)) {
                $this->fields[$field] = $value;
            }
        }
    }

    public function initiative(): string
    {
        return $this->initiative;
    }

    public function isTypeUpdateRequested(): bool
    {
        return key_exists(InitiativeContentModification::TYPE, $this->fields);
    }

    public function isTitleUpdateRequested(): bool
    {
        return key_exists(InitiativeContentModification::TITLE, $this->fields);
    }

    public function isDescriptionUpdateRequested(): bool
    {
        return key_exists(InitiativeContentModification::DESCRIPTION, $this->fields);
    }

    public function isMinimalJoinedPeopleUpdateRequested(): bool
    {
        return key_exists(InitiativeContentModification::MINIMAL_JOINED_PEOPLE, $this->fields);
    }

    public function type(): ?string
    {
        return $this->isTypeUpdateRequested()
            ? $this->fields[InitiativeContentModification::TYPE]
            : null;
    }

    public function title(): ?string
    {
        return $this->isTitleUpdateRequested()
            ? (string) $this->fields[InitiativeContentModification::TITLE]
            : null;
    }

    public function description(): ?string
    {
        return $this->isDescriptionUpdateRequested()
            ? (string) $this->fields[InitiativeContentModification::DESCRIPTION]
            : null;
    }

    public function minimalJoinedPeople(): ?int
    {
        return $this->isMinimalJoinedPeopleUpdateRequested()
            ? $this->fields[InitiativeContentModification::MINIMAL_JOINED_PEOPLE]
            : null;
    }

    public function isEndingDateUpdateRequested(): bool
    {
        return key_exists(InitiativeContentModification::DATE_END, $this->fields);
    }

    public function isStartingDateUpdateRequested(): bool
    {
        return key_exists(InitiativeContentModification::DATE_START, $this->fields);
    }

    public function isDateUpdateRequested(): bool
    {
        return $this->isStartingDateUpdateRequested() || $this->isEndingDateUpdateRequested();
    }

    public function dateStart(): ?string
    {
        return $this->isStartingDateUpdateRequested()
            ? $this->fields[InitiativeContentModification::DATE_START]
            : null;
    }

    public function dateEnd(): ?string
    {
        return $this->isEndingDateUpdateRequested()
            ? $this->fields[InitiativeContentModification::DATE_END]
            : null;
    }

    public function isLocationUpdateRequested(): bool
    {
        return key_exists(InitiativeContentModification::LOCATION, $this->fields);
    }

    public function isLocationNameUpdateRequested(): bool
    {
        return key_exists(InitiativeContentModification::LOCATION_NAME, $this->fields);
    }

    public function location(): ?string
    {
        return $this->isLocationUpdateRequested()
            ? $this->fields[InitiativeContentModification::LOCATION]
            : null;
    }

    public function locationName(): ?string
    {
        return $this->isLocationUpdateRequested()
            ? $this->fields[InitiativeContentModification::LOCATION_NAME]
            : null;
    }

    public function isLocationRadiusUpdateRequested(): bool
    {
        return key_exists(InitiativeContentModification::LOCATION_RADIUS_VALUE, $this->fields);
    }

    public function locationRadiusValue(): ?float
    {
        return $this->isLocationRadiusUpdateRequested()
            ? $this->fields[InitiativeContentModification::LOCATION_RADIUS_VALUE]
            : null;
    }

    public function isLocationRadiusUnitUpdateRequested(): bool
    {
        return key_exists(InitiativeContentModification::LOCATION_RADIUS_UNIT, $this->fields);
    }

    public function locationRadiusUnit(): ?string
    {
        return $this->isLocationRadiusUnitUpdateRequested()
            ? $this->fields[InitiativeContentModification::LOCATION_RADIUS_UNIT]
            : null;
    }

    public function hasLocationUpdateRequest(): bool
    {
        return $this->isLocationRadiusUnitUpdateRequested()
            || $this->isLocationRadiusUpdateRequested()
            || $this->isLocationUpdateRequested();
    }

    public function hasFullLocationUpdateRequest(): bool
    {
        return $this->isLocationRadiusUnitUpdateRequested()
            && $this->isLocationRadiusUpdateRequested()
            && $this->isLocationNameUpdateRequested()
            && $this->isLocationUpdateRequested();
    }

    public function category(): ?string
    {
        return $this->isCategoryUpdateRequested()
            ? $this->fields[InitiativeContentModification::CATEGORY]
            : null;
    }
    public function questionnaires(): ?array
    {
        return $this->isQuestionnairesUpdateRequested()
            ? $this->fields[InitiativeContentModification::QUESTIONNAIRES]
            : null;
    }

    public function isCategoryUpdateRequested(): bool
    {
        return key_exists(InitiativeContentModification::CATEGORY, $this->fields);
    }

    public function isImagesUpdateRequested(): bool
    {
        return key_exists(InitiativeContentModification::IMAGES, $this->fields);
    }

    public function isImagesToRemoveUpdateRequested(): bool
    {
        return key_exists(InitiativeContentModification::IMAGES_TO_REMOVE, $this->fields);
    }

    public function isQuestionnairesUpdateRequested(): bool
    {
        return key_exists(InitiativeContentModification::QUESTIONNAIRES, $this->fields);
    }

    public function images(): ?PreUploadedImageCollection
    {
        return $this->isImagesUpdateRequested()
            ? $this->fields[InitiativeContentModification::IMAGES]
            : null;
    }

    public function imagesToRemove(): ?array
    {
        return $this->isImagesToRemoveUpdateRequested()
            ? $this->fields[InitiativeContentModification::IMAGES_TO_REMOVE]
            : null;
    }
}
