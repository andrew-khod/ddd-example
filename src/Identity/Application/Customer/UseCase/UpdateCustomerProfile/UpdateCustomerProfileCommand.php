<?php

declare(strict_types=1);

namespace App\Identity\Application\Customer\UseCase\UpdateCustomerProfile;

use App\Shared\Domain\PreUploadedImage\PreUploadedImageCollection;
use DateTime;

final class UpdateCustomerProfileCommand
{
    private array $fields = [];

    public function __construct(array $fields)
    {
        foreach ($fields as $field => $value) {
            if (CustomerProfileModification::isAllowedField($field)) {
                $this->fields[$field] = $value;
            }
        }
    }

    public function username(): ?string
    {
        return $this->isUsernameUpdateRequested()
            ? $this->fields[CustomerProfileModification::USERNAME]
            : null;
    }

    public function birthday(): ?DateTime
    {
        return $this->isBirthdayUpdateRequested() && $this->fields[CustomerProfileModification::BIRTHDAY]
            ? new DateTime($this->fields[CustomerProfileModification::BIRTHDAY])
            : null;
    }

    public function gender(): ?string
    {
        return $this->isGenderUpdateRequested() && $this->fields[CustomerProfileModification::GENDER]
            ? $this->fields[CustomerProfileModification::GENDER]
            : null;
    }

    public function postal(): ?string
    {
        return $this->isPostalUpdateRequested() && $this->fields[CustomerProfileModification::POSTAL]
            ? $this->fields[CustomerProfileModification::POSTAL]
            : null;
    }

    public function password(): ?string
    {
        return $this->isPasswordUpdateRequested()
            ? $this->fields[CustomerProfileModification::NEW_PASSWORD]
            : null;
    }

    public function isPhotoUpdateRequested(): bool
    {
        return key_exists(CustomerProfileModification::PHOTO, $this->fields);
    }

    public function photo(): ?PreUploadedImageCollection
    {
        return $this->isPhotoUpdateRequested()
            ? $this->fields[CustomerProfileModification::PHOTO]
            : null;
    }

    public function isUsernameUpdateRequested(): bool
    {
        return key_exists(CustomerProfileModification::USERNAME, $this->fields);
    }

    public function isBirthdayUpdateRequested(): bool
    {
        return key_exists(CustomerProfileModification::BIRTHDAY, $this->fields);
    }

    public function isGenderUpdateRequested(): bool
    {
        return key_exists(CustomerProfileModification::GENDER, $this->fields);
    }

    public function isPostalUpdateRequested(): bool
    {
        return key_exists(CustomerProfileModification::POSTAL, $this->fields);
    }

    public function isPasswordUpdateRequested(): bool
    {
        return key_exists(CustomerProfileModification::NEW_PASSWORD, $this->fields);
    }
}
