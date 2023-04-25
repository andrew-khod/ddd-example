<?php

declare(strict_types=1);

namespace App\Customization\Application\UseCase\UpdateTermsOfUse;

use App\Customization\Application\Query\TermsOfUseByCriteriaQuery;
use App\Customization\Application\TermsOfUseEntityManager;
use App\Customization\Domain\TermsOfUse;
use App\Shared\Application\AssignedToCompanyLanguagesQuery;
use App\Shared\Application\LanguageNotExistException;
use App\Shared\Domain\Language;

final class UpdateTermsOfUseHandler
{
    private TermsOfUseEntityManager $termsOfUseEntityManager;
    private AssignedToCompanyLanguagesQuery $allLanguageQuery;
    private TermsOfUseByCriteriaQuery $termsByCriteriaQuery;

    public function __construct(TermsOfUseEntityManager         $termsOfUseEntityManager,
                                AssignedToCompanyLanguagesQuery $allLanguageQuery,
                                TermsOfUseByCriteriaQuery       $termsByCriteriaQuery)
    {
        $this->termsOfUseEntityManager = $termsOfUseEntityManager;
        $this->allLanguageQuery = $allLanguageQuery;
        $this->termsByCriteriaQuery = $termsByCriteriaQuery;
    }

    public function handle(UpdateTermsOfUseCommand $command): void
    {
        $languages = $this->allLanguageQuery->query();
//        $this->termsOfUseEntityManager->deleteAll(TermsOfUse::class);
        $this->termsOfUseEntityManager->deleteAll();
        // todo if !termss->count() then create new else update instead of removing all termss
//        $termss = $this->termsByCriteriaQuery->queryMultiple(new AccessibilityAllPolicyCriteria());

        foreach ($command->terms() as $language => $terms) {
            // todo move lang logic to langrepo->getByStringLang
            $language = array_values(array_filter(
                $languages,
                fn(Language $l) => $l->name() === $language
            ));
            $language = $language
                ? $language[0]
                : throw new LanguageNotExistException();

            // todo wrap $terms array to more strict structure
            $this->termsOfUseEntityManager->create(new TermsOfUse($terms['title'], $terms['description'], $language));
        }

        $this->termsOfUseEntityManager->flush();
    }
}
