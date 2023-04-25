<?php

namespace App\Customization\Application\UseCase\Web\CreateFeedback;

interface Feedback
{
    public function create(string $email, string $message, string $name, string $section): void;
}