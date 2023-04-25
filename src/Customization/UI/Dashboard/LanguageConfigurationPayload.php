<?php

declare(strict_types=1);

namespace App\Customization\UI\Dashboard;

final class LanguageConfigurationPayload
{
    private array $availableLanguages;
    private array $assignedLanguages;

    public function __construct(array $availableLanguages, array $assignedLanguages)
    {
        $this->availableLanguages = $availableLanguages;
        $this->assignedLanguages = $assignedLanguages;
    }

    public function availableLanguages(): array
    {
        return $this->availableLanguages;
    }

    public function assignedLanguages(): array
    {
        return $this->assignedLanguages;
    }
}