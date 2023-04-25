<?php

declare(strict_types=1);

namespace App\Customization\Domain;

use App\Shared\Domain\ActiveLanguage;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Uid\Uuid;

//class Question implements Entity
class Question
{
    private Uuid $id;
    private int $orderIndex;
    private DateTime $created;
    private DateTime $deleted;
    private Collection $translations;

//    public function __construct(array $translations)
    public function __construct(int $order)
    {
        $this->id = Uuid::v4();
        $this->orderIndex = $order;

        // todo
        $this->translations = new ArrayCollection();
    }

    public function id(): Uuid
    {
        return $this->id;
    }

    // todo extract byActiveLanguage methods to Domain Service?
    public function question(ActiveLanguage $language): string
    {
        return $this->translation($language)?->question() ?: 'not translated';
    }

    public function answer(ActiveLanguage $language): string
    {
        return $this->translation($language)?->answer() ?: 'not translated';
    }

    // fixme exposing child entities from Aggregate here. is there a best way?
    public function translations(): QuestionTranslationCollection
    {
        return new QuestionTranslationCollection(...$this->translations->toArray());
//        return new QuestionTranslationCollection(...$this->translations->filter(fn(QuestionTranslation $translation) => $translation->languageAsObject()->isActive())->toArray());
    }

    private function translation(ActiveLanguage $language): ?QuestionTranslation
    {
        $requestedTranslation = $this->translations->filter(fn(QuestionTranslation $translation) => $translation->language() === $language->language())->first();

        return $requestedTranslation ?: null;

        if ($requestedTranslation) {
            return $requestedTranslation;
        }

        $defaultTranslation = $this->translations->filter(
            fn(QuestionTranslation $translation) => $translation->language() === $language::DEFAULT_LANGUAGE
        )->first();

        if ($defaultTranslation) {
            return $defaultTranslation;
        }

        return $this->translations->first() ?: null;
    }

    public function translate(QuestionTranslation $translation): void
    {
        $this->translations->add($translation);
    }
}
