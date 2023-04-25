<?php

declare(strict_types=1);

namespace App\Customization\Infrastructure;

use App\Customization\Application\UseCase\Web\CreateFeedback\Captcha as CaptchaAlias;
use ReCaptcha\ReCaptcha;

final class Captcha implements CaptchaAlias
{
    private ReCaptcha $captcha;

    public function __construct(ReCaptcha $captcha)
    {
        $this->captcha = $captcha;
    }

    public function verify(string $token): bool
    {
        $verify = $this->captcha->verify($token);
        var_dump($verify->getErrorCodes());
        return $verify->isSuccess();
    }
}