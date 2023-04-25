<?php

declare(strict_types=1);

namespace App\Identity\Domain\Customer;

class GeneralInformation
{
    private ?Username $username;
    private ?Birthday $birthday;
    private ?Gender $gender;
    private ?Postal $postal;
    private ?string $photo;

    public function __construct(?Username $username = null,
                                ?Birthday $birthday = null,
                                ?Gender $gender = null,
                                ?Postal $postal = null,
                                ?string $photo = null)
    {
        $this->username = $username;
        $this->birthday = $birthday;
        $this->gender = $gender;
        $this->postal = $postal;
        $this->photo = $photo;
    }

    public function username(): ?Username
    {
        return $this->username;
    }

    public function birthday(): ?Birthday
    {
        return $this->birthday;
    }

    public function postal(): ?Postal
    {
        return $this->postal;
    }

    public function gender(): ?Gender
    {
        return $this->gender;
    }

    public function photo(): ?string
    {
        return $this->photo;
    }
}
