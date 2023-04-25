<?php

declare(strict_types=1);

namespace App\Customization\Application\UseCase\UpdateCookiesPolicy;

use App\Customization\Application\CookiesPolicyEntityManager;
use App\Customization\Application\Query\CookiesPolicyByCriteriaQuery;
use App\Customization\Domain\CookiesPolicy;
use App\Shared\Application\AssignedToCompanyLanguagesQuery;
use App\Shared\Application\LanguageNotExistException;
use App\Shared\Domain\Language;

final class UpdateCookiesPolicyHandler
{
    private CookiesPolicyEntityManager $policyEntityManager;
    private AssignedToCompanyLanguagesQuery $allLanguageQuery;
    private CookiesPolicyByCriteriaQuery $policyByCriteriaQuery;

    public function __construct(CookiesPolicyEntityManager      $policyEntityManager,
                                AssignedToCompanyLanguagesQuery $allLanguageQuery,
                                CookiesPolicyByCriteriaQuery    $policyByCriteriaQuery)
    {
        $this->policyEntityManager = $policyEntityManager;
        $this->allLanguageQuery = $allLanguageQuery;
        $this->policyByCriteriaQuery = $policyByCriteriaQuery;
    }

    public function handle(UpdateCookiesPolicyCommand $command): void
    {
        $languages = $this->allLanguageQuery->query();
//        $this->policyEntityManager->deleteAll(CookiesPolicy::class);
        $this->policyEntityManager->deleteAll();
        // todo if !policys->count() then create new else update instead of removing all policys
//        $policys = $this->policyByCriteriaQuery->queryMultiple(new CookiesAllPolicyCriteria());

        foreach ($command->policy() as $language => $policy) {
            // todo move lang logic to langrepo->getByStringLang
            $language = array_values(array_filter(
                $languages,
                fn(Language $l) => $l->name() === $language
            ));
            $language = $language
                ? $language[0]
                : throw new LanguageNotExistException();

            // todo wrap $policy array to more strict structure
            $this->policyEntityManager->create(new CookiesPolicy($policy['title'], $policy['description'], $language));
        }

        $this->policyEntityManager->flush();
    }
}
