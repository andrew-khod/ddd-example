<?php

declare(strict_types=1);

namespace App\Initiative\Domain\Category;

use App\Shared\Domain\Language;

final class NewCategoryTranslation
{
    private string $name;
    private Language $language;

    public function __construct(string $name, Language $language)
    {
        $this->name = $name;
        $this->language = $language;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function language(): Language
    {
        return $this->language;
    }
}