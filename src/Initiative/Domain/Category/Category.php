<?php

declare(strict_types=1);

namespace App\Initiative\Domain\Category;

use App\Initiative\Domain\Initiative\InitiativeCollection;
use App\Shared\Domain\ActiveLanguage;
use App\Shared\Domain\Entity;
use App\Shared\Domain\Language;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Category implements Entity
{
    private CategoryId $id;

    private Collection $initiatives;

    private Collection $translations;

//    public function __construct(CategoryId $id, string $name)
    public function __construct(CategoryId $id, NewCategoryTranslation ...$translations)
    {
        $this->id = $id;
        $this->translations = new ArrayCollection(
            array_map(
                fn(NewCategoryTranslation $translation) => new CategoryTranslation($translation->name(), $this, $translation->language()
            ), $translations)
        );
    }

    public function id(): CategoryId
    {
        return $this->id;
    }

    public function translations(): Collection
    {
        return $this->translations;
    }

    public function name(ActiveLanguage $language): string
    {
        $requestedTranslation = $this->translations->filter(function (CategoryTranslation $translation) use ($language) {
            return $translation->language() === $language->language();
        })->first();

        if ($requestedTranslation) {
            return $requestedTranslation->name();
        }

        $defaultTranslation = $this->translations->filter(function (CategoryTranslation $translation) use ($language) {
            return $translation->language() === $language::DEFAULT_LANGUAGE;
        })->first();

        if ($defaultTranslation) {
            return $defaultTranslation->name();
        }

        $translation = $this->translations->first();
        return $translation ? $translation->name() : 'not translated';
    }

    public function renameOrCreateTranslation(Language $language, string $name): void
    {
        $translation = $this->translations->filter(
            fn(CategoryTranslation $translation) => $translation->language() === (string) $language
        )->first();

        if ($translation) {
            $translation->rename($name);
        } else {
            $this->translations->add(new CategoryTranslation($name, $this, $language));
        }
    }

    public function initiatives(): InitiativeCollection
    {
        return new InitiativeCollection(...$this->initiatives->toArray());
    }
}
