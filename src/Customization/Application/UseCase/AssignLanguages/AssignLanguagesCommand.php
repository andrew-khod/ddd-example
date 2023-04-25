<?php

declare(strict_types=1);

namespace App\Customization\Application\UseCase\AssignLanguages;

final class AssignLanguagesCommand
{
    private array $languages;

    public function __construct(array $languages)
    {
        $this->languages = $languages;
    }

    public function languages(): array
    {
        return $this->languages;
    }
}
