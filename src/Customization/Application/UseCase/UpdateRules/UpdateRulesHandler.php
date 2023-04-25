<?php

declare(strict_types=1);

namespace App\Customization\Application\UseCase\UpdateRules;

use App\Customization\Application\Query\RuleByCriteriaQuery;
use App\Customization\Application\RuleEntityManager;
use App\Customization\Domain\Rule;
use App\Shared\Application\AssignedToCompanyLanguagesQuery;
use App\Shared\Application\LanguageNotExistException;
use App\Shared\Domain\Language;

final class UpdateRulesHandler
{
    private RuleEntityManager $ruleEntityManager;
    private AssignedToCompanyLanguagesQuery $allLanguageQuery;
    private RuleByCriteriaQuery $ruleByCriteriaQuery;

    public function __construct(RuleEntityManager               $ruleEntityManager,
                                AssignedToCompanyLanguagesQuery $allLanguageQuery,
                                RuleByCriteriaQuery             $ruleByCriteriaQuery)
    {
        $this->ruleEntityManager = $ruleEntityManager;
        $this->allLanguageQuery = $allLanguageQuery;
        $this->ruleByCriteriaQuery = $ruleByCriteriaQuery;
    }

    public function handle(UpdateRulesCommand $command): void
    {
        $languages = $this->allLanguageQuery->query();
//        $this->ruleEntityManager->deleteAll(Rule::class);
        $this->ruleEntityManager->deleteAll();
        // todo if !rules->count() then create new else update instead of removing all rules
//        $rules = $this->ruleByCriteriaQuery->queryMultiple(new AllRuleCriteria());

        foreach ($command->rules() as $language => $rule) {
            // todo move lang logic to langrepo->getByStringLang
            $language = array_values(array_filter(
                $languages,
                fn(Language $l) => $l->name() === $language
            ));
            $language = $language
                ? $language[0]
                : throw new LanguageNotExistException();

            // todo wrap $rule array to more strict structure
            $this->ruleEntityManager->create(new Rule($rule['title'], $rule['description'], $language));
        }

        $this->ruleEntityManager->flush();
    }
}
