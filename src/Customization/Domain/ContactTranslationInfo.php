<?php

declare(strict_types=1);

namespace App\Customization\Domain;

use App\Shared\Domain\Language;

// todo implement Iterable
final class ContactTranslationInfo
{
    private array $translations;
    private Language $language;

    public function __construct(Language $language, array $translations)
    {
        // todo reject if empty & validate props
        $this->language = $language;
        $this->translations = $translations;
    }

    public function language(): Language
    {
        return $this->language;
    }

    public function translations(): array
    {
        return $this->translations;
    }
}