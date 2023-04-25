<?php

declare(strict_types=1);

namespace App\Customization\Application\UseCase\UpdatePrivacyPolicy;

use App\Customization\Application\PrivacyPolicyEntityManager;
use App\Customization\Application\Query\PrivacyPolicyByCriteriaQuery;
use App\Customization\Domain\PrivacyPolicy;
use App\Shared\Application\AssignedToCompanyLanguagesQuery;
use App\Shared\Application\LanguageNotExistException;
use App\Shared\Domain\Language;

final class UpdatePrivacyPolicyHandler
{
    private PrivacyPolicyEntityManager $policyEntityManager;
    private AssignedToCompanyLanguagesQuery $allLanguageQuery;
    private PrivacyPolicyByCriteriaQuery $policyByCriteriaQuery;

    public function __construct(PrivacyPolicyEntityManager      $policyEntityManager,
                                AssignedToCompanyLanguagesQuery $allLanguageQuery,
                                PrivacyPolicyByCriteriaQuery    $policyByCriteriaQuery)
    {
        $this->policyEntityManager = $policyEntityManager;
        $this->allLanguageQuery = $allLanguageQuery;
        $this->policyByCriteriaQuery = $policyByCriteriaQuery;
    }

    public function handle(UpdatePrivacyPolicyCommand $command): void
    {
        $languages = $this->allLanguageQuery->query();
//        $this->policyEntityManager->deleteAll(PrivacyPolicy::class);
        $this->policyEntityManager->deleteAll();
        // todo if !policy->count() then create new else update instead of removing all policy
//        $policy = $this->policyByCriteriaQuery->queryMultiple(new AllPrivacyPolicyCriteria());

        foreach ($command->policy() as $language => $url) {
            // todo move lang logic to langrepo->getByStringLang
            $language = array_values(array_filter(
                $languages,
                fn(Language $l) => $l->name() === $language
            ));
            $language = $language
                ? $language[0]
                : throw new LanguageNotExistException();

            $this->policyEntityManager->create(new PrivacyPolicy($url, $language));
        }

        $this->policyEntityManager->flush();
    }
}
