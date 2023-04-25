<?php

declare(strict_types=1);

namespace App\Initiative\Domain\Category;

use App\Shared\Domain\Language;

//class CategoryTranslation implements Entity
class CategoryTranslation
{
    private string $name;

    private Category $category;

    private Language $language;

    public function __construct(string $name, Category $category, Language $language)
    {
        $this->category = $category;
        $this->language = $language;
        $this->name = $name;
    }

    // todo
//    public function id(): CategoryId
    public function id()
    {
        return 'asdasdasd';
    }

    public function language(): string
    {
        return $this
            ->language
            ->name();
    }

    public function name(): string
    {
        return $this->name;
    }

    public function rename(string $name): void
    {
        $this->name = $name;
    }
}
