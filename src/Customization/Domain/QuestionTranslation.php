<?php

declare(strict_types=1);

namespace App\Customization\Domain;

use App\Shared\Domain\Language;

//class Question implements Entity
class QuestionTranslation
{
    private string $title;
    private string $answer;
    private Question $question;
    private Language $language;

    public function __construct(string $title,
                                string $answer,
                                Question $question,
                                Language $language)
    {
        $this->title = $title;
        $this->answer = $answer;
        $this->language = $language;
        $this->question = $question;
    }

    public function question(): string
    {
        return $this->title;
    }

    public function answer(): string
    {
        return $this->answer;
    }

    public function language(): string
    {
        return $this->language->name();
    }

    public function languageAsObject(): Language
    {
        return $this->language;
    }
}
