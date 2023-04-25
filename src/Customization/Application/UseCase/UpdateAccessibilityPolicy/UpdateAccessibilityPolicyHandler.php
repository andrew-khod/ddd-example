<?php

declare(strict_types=1);

namespace App\Customization\Application\UseCase\UpdateAccessibilityPolicy;

use App\Customization\Application\AccessibilityPolicyEntityManager;
use App\Customization\Application\Query\AccessibilityPolicyByCriteriaQuery;
use App\Customization\Domain\AccessibilityPolicy;
use App\Shared\Application\AssignedToCompanyLanguagesQuery;
use App\Shared\Application\LanguageNotExistException;
use App\Shared\Domain\Language;

final class UpdateAccessibilityPolicyHandler
{
    private AccessibilityPolicyEntityManager $policyEntityManager;
    private AssignedToCompanyLanguagesQuery $allLanguageQuery;
    private AccessibilityPolicyByCriteriaQuery $policyByCriteriaQuery;

    public function __construct(AccessibilityPolicyEntityManager   $policyEntityManager,
                                AssignedToCompanyLanguagesQuery    $allLanguageQuery,
                                AccessibilityPolicyByCriteriaQuery $policyByCriteriaQuery)
    {
        $this->policyEntityManager = $policyEntityManager;
        $this->allLanguageQuery = $allLanguageQuery;
        $this->policyByCriteriaQuery = $policyByCriteriaQuery;
    }

    public function handle(UpdateAccessibilityPolicyCommand $command): void
    {
        $languages = $this->allLanguageQuery->query();
//        $this->policyEntityManager->deleteAll(AccessibilityPolicy::class);
        $this->policyEntityManager->deleteAll();
        // todo if !policys->count() then create new else update instead of removing all policys
//        $policys = $this->policyByCriteriaQuery->queryMultiple(new AccessibilityAllPolicyCriteria());

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
            $this->policyEntityManager->create(new AccessibilityPolicy($policy['title'], $policy['description'], $language));
        }

        $this->policyEntityManager->flush();
    }
}
