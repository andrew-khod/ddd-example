<?php

declare(strict_types=1);

namespace App\Initiative\Application\Questionnaire\UseCase\AnswerQuestionnaires;

final class AnswerQuestionnairesCommand
{
    private string $initiative;
    private array $answers;

    public function __construct(string $initiative, array $answers)
    {
        $this->initiative = $initiative;
        $this->answers = $answers;
    }

    public function initiative(): string
    {
        return $this->initiative;
    }

    public function answers(): array
    {
        return $this->answers;
    }
}
