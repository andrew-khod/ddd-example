<?php

namespace App\Customization\UI\Dashboard;

use App\Customization\Domain\QuestionCollection;
use App\Shared\UI\DashboardPayload;

class QuestionCollectionPayload implements DashboardPayload
{
    private QuestionCollection $questions;

    public function __construct(QuestionCollection $questions)
    {
        $this->questions = $questions;
    }

    public function get(): QuestionCollection
    {
        return $this->questions;
    }
}