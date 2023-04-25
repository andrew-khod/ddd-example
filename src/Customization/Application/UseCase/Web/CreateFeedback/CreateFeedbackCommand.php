<?php

declare(strict_types=1);

namespace App\Customization\Application\UseCase\Web\CreateFeedback;

final class CreateFeedbackCommand
{

    private string $email;
    private string $message;
    private string $section;
    private string $name;
    private string $captcha;

    public function __construct(string $captcha,
                                string $email,
                                string $message,
                                string $section,
                                string $name)
    {
        $this->email = $email;
        $this->message = $message;
        $this->section = $section;
        $this->name = $name;
        $this->captcha = $captcha;
    }

    public function email(): string
    {
        return $this->email;
    }

    public function message(): string
    {
        return $this->message;
    }

    public function section(): string
    {
        return $this->section;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function captcha(): string
    {
        return $this->captcha;
    }
}
