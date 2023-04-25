<?php

declare(strict_types=1);

namespace App\Customization\Application\UseCase\UpdateQuestions;

final class UpdateQuestionsCommand
{
    private array $questions;

    public function __construct(array $questions)
    {
        $this->questions = $questions;
    }

    public function questions(): array
    {
        return $this->questions;
    }
}
