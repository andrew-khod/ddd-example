<?php

declare(strict_types=1);

namespace App\Customization\Domain;

use App\Shared\Domain\ActiveLanguage;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Uid\Uuid;

class Contact
{
    private Uuid $id;
    private int $orderIndex;
    private DateTime $created;
    private DateTime $deleted;
    private Collection $translations;

    public function __construct(int $order, ContactTranslationInfo ...$translations)
    {
        $this->id = Uuid::v4();
        $this->orderIndex = $order;

        $this->translations = new ArrayCollection();

        foreach ($translations as $translation) {
            // todo validate translations inside ContactTranslationInfo
            foreach ($translation->translations() as $info) {
                $this->translations->add(new ContactTranslation($info['type'], $info['value'], $this, $translation->language()));
            }
        }
    }

    public function id(): Uuid
    {
        return $this->id;
    }

    // fixme exposing child entities from Aggregate here. is there a best way?
    public function translations(ActiveLanguage $language = null): ContactTranslationCollection
    {
        if (!$language) {
            $translations = $this->translations->toArray();
//            $translations = $this->translations->filter(
//                fn(ContactTranslation $translation) => $translation->languageAsObject()->isActive()
//            )->toArray();
        } else {
            $translations = $this->translations->filter(
                fn(ContactTranslation $translation) => $translation->language() === $language->language()
            )->toArray();
        }

        return new ContactTranslationCollection(...$translations);
    }

    private function translation(ActiveLanguage $language): ?ContactTranslation
    {
        $requestedTranslation = $this->translations->filter(fn(ContactTranslation $translation) => $translation->language() === $language->language())->first();

        if ($requestedTranslation) {
            return $requestedTranslation;
        }

        $defaultTranslation = $this->translations->filter(fn(ContactTranslation $translation) => $translation->language() === $language::DEFAULT_LANGUAGE)->first();

        if ($defaultTranslation) {
            return $defaultTranslation;
        }

        return $this->translations->first() ?: null;
    }
}
