<?php

declare(strict_types=1);

namespace App\Customization\Infrastructure;

use App\Customization\Application\Query\QuestionCriteria;
use Happyr\DoctrineSpecification\Spec;
use Happyr\DoctrineSpecification\Specification\BaseSpecification;

class QuestionByLanguageCriteria extends BaseSpecification implements QuestionCriteria
{
    private string $language;

    public function __construct(string $language, ?string $context = null)
    {
        parent::__construct($context);
        $this->language = $language;
    }

    protected function getSpec()
    {
        return Spec::andX(
            new AllQuestionCriteria(),
            Spec::join('language', 'language', 'translations'),
            Spec::eq('name', $this->language, 'translations.language'),
        );
    }
}