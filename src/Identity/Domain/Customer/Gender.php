<?php

declare(strict_types=1);

namespace App\Identity\Domain\Customer;

class Gender
{
    private const GENDERS = [
        'm' => 'male', 'f' => 'female', 'o' => 'other',
    ];

    private string $gender;

    public function __construct(string $gender)
    {
        if ($this->validateByFull($gender)) {
            $this->gender = $gender;
        } elseif ($this->validateByShort($gender)) {
            $this->gender = self::GENDERS[$gender];
        } else {
            throw new InvalidGenderException();
        }
    }

    public static function genders(): array
    {
        return [...array_values(self::GENDERS), ...array_keys(self::GENDERS)];
    }

    public function value(): string
    {
        return strtolower($this->gender[0]);
    }

    public function __toString(): string
    {
        return $this->value();
    }

    private function validate(string $gender, array $genders): bool
    {
        $genders = array_map(
            fn (string $gender) => strtolower($gender),
            array_values($genders)
        );
        $gender = strtolower($gender);

        return in_array($gender, $genders);
    }

    private function validateByShort(string $gender): bool
    {
        return $this->validate($gender, array_keys(self::GENDERS));
    }

    private function validateByFull(string $gender): bool
    {
        return $this->validate($gender, array_values(self::GENDERS));
    }
}
