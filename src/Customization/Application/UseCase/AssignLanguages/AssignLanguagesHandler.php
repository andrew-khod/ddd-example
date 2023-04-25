<?php

declare(strict_types=1);

namespace App\Customization\Application\UseCase\AssignLanguages;

use App\Customization\Application\LanguageEntityManager;
use App\Shared\Application\AssignedToCompanyLanguagesQuery;
use App\Shared\Application\AvailableLanguagesQuery;
use App\Shared\Domain\AvailableLanguage;
use App\Shared\Domain\Language;

final class AssignLanguagesHandler
{
    private AvailableLanguagesQuery $availableLanguagesQuery;
    private AssignedToCompanyLanguagesQuery $assignedToCompanyLanguagesQuery;
    private LanguageEntityManager $languageEntityManager;

    public function __construct(AvailableLanguagesQuery         $availableLanguagesQuery,
                                LanguageEntityManager $languageEntityManager,
                                AssignedToCompanyLanguagesQuery $assignedToCompanyLanguagesQuery)
    {
        $this->availableLanguagesQuery = $availableLanguagesQuery;
        $this->assignedToCompanyLanguagesQuery = $assignedToCompanyLanguagesQuery;
        $this->languageEntityManager = $languageEntityManager;
    }

    public function handle(AssignLanguagesCommand $command): void
    {
        // todo optimize loops
        $this->assignedToCompanyLanguagesQuery->includeDeleted();
        $toAssign = array_filter(
            $this->availableLanguagesQuery->query(),
            fn(AvailableLanguage $language) => in_array((string) $language->name(), $command->languages())
        );
        $toAssignInString = array_map(fn(AvailableLanguage $language) => (string) $language, $toAssign);
        $assigned = $this->assignedToCompanyLanguagesQuery->query();
        $assignedInString = array_map(fn(Language $language) => (string) $language, $assigned);
//        $assignedInString = array_map(fn(Language $language) => (string) $language, array_filter($assigned, fn(Language $language) => $language->isActive()));
//        $toAdd = array_filter($toAssign, fn(AvailableLanguage $language) => !in_array((string) $language, $assignedInString));
        $toActivate = array_filter($assigned, fn(Language $language) => !$language->isActive());
        $toRemove = array_filter($assigned, fn(Language $language) => !in_array((string) $language, $toAssignInString));
        $toAdd = array_reduce($toAssign, function ($acc, AvailableLanguage $language) use ($assignedInString) {
            if (!in_array((string) $language, $assignedInString)) {
                $acc[] = new Language($this->languageEntityManager->nextId(), $language->name());
            }
            return $acc;
        }, []);

//        $newLanguages = array_uintersect(
//            $availableLanguages, $assignedLanguages,
//            fn($assignedLanguage, $availableLanguage) => $availableLanguage->equals($assignedLanguage)
////            fn(Language $assignedLanguage, AvailableLanguage $availableLanguage) => $availableLanguages->equals($assignedLanguage)
//        );

        array_walk($toActivate, fn(Language $language) => $language->activate());

        foreach ($toRemove as $language) {
            $this->languageEntityManager->delete($language);
        }

        foreach ($toAdd as $language) {
            $this->languageEntityManager->create($language);
        }

        $this->languageEntityManager->persist();
    }
}
