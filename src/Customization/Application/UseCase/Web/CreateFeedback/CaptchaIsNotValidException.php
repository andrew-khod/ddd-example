<?php

declare(strict_types=1);

namespace App\Customization\Application\UseCase\Web\CreateFeedback;

use App\Shared\Application\HttpResponseCode;
use App\Shared\Domain\BaseException;
use Exception;
use Throwable;

class CaptchaIsNotValidException extends Exception implements BaseException
{
    public function __construct($message = '', $code = 0, Throwable $previous = null)
    {
        parent::__construct('Captcha is not invalid', HttpResponseCode::badRequest(), $previous);
    }

    public function getErrors(): array
    {
        return ['CaptchaException' => $this->message];
    }
}