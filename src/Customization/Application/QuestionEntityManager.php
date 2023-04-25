<?php

namespace App\Customization\Application;

use App\Customization\Domain\Question;

interface QuestionEntityManager
{
    public function deleteAll(): void;

    public function create(Question $question): void;

    public function flush(): void;
}