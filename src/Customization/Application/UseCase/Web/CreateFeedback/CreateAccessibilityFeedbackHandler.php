<?php

declare(strict_types=1);

namespace App\Customization\Application\UseCase\Web\CreateFeedback;

final class CreateAccessibilityFeedbackHandler
{
    private Feedback $feedback;
    private Captcha $captcha;

    public function __construct(Feedback $accessibilityFeedback, Captcha $captcha)
    {
        $this->feedback = $accessibilityFeedback;
        $this->captcha = $captcha;
    }

    public function handle(CreateFeedbackCommand $command): void
    {
        if (!$this->captcha->verify($command->captcha())) {
            throw new CaptchaIsNotValidException();
        }

        $this->feedback->create(
            $command->email(),
            $command->message(),
            $command->name(),
            $command->section()
        );
    }
}
