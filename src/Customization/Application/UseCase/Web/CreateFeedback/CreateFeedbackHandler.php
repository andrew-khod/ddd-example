<?php

declare(strict_types=1);

namespace App\Customization\Application\UseCase\Web\CreateFeedback;

final class CreateFeedbackHandler
{
    private Feedback $feedback;
    private Captcha $captcha;

    public function __construct(Feedback $feedback, Captcha $captcha)
    {
        $this->feedback = $feedback;
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
