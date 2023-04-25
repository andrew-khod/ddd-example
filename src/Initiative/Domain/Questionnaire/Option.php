<?php

declare(strict_types=1);

namespace App\Initiative\Domain\Questionnaire;

use Symfony\Component\Uid\Uuid;

class Option
{
    private Uuid $id;
    private ?Questionnaire $questionnaire = null;
    private string $option;

    public function __construct(Questionnaire $questionnaire, string $option)
//    public function __construct(string $option)
    {
        $this->id = Uuid::v4();
        $this->questionnaire = $questionnaire;
        $this->option = $option;
    }

//    public function assignToQuestionnaire(Questionnaire $questionnaire): void
//    {
//        $this->questionnaire = $questionnaire;
//    }
    public function title(): string
    {
        return $this->option;
    }

    public function changeTitle(string $title): void
    {
        $this->option = $title;
    }

    public function remove(): void
    {
        $this->questionnaire = null;
    }

    public function id(): Uuid
    {
        return $this->id;
    }
}