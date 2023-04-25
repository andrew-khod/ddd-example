<?php

namespace App\Customization\Application\UseCase\Web\CreateFeedback;

interface Captcha
{
    public function verify(string $token): bool;
}