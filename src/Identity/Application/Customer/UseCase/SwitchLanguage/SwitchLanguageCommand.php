<?php

declare(strict_types=1);

namespace App\Identity\Application\Customer\UseCase\SwitchLanguage;

final class SwitchLanguageCommand
{
    private string $language;

    public function __construct(string $language)
    {
        $this->language = $language;
    }

    public function language(): string
    {
        return $this->language;
    }
}
