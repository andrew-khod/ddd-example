<?php

namespace App\Identity\Application\User\UseCase\LinkGoogleAuth;

use App\Identity\Domain\User\UserTranslation;
use App\Shared\Application\HttpResponseCode;
use App\Shared\Domain\BaseException;
use Exception;
use Throwable;

final class InvalidOneTimeCodeException extends Exception implements BaseException
{
    public function __construct($message = '', $code = 0, Throwable $previous = null)
    {
        parent::__construct(UserTranslation::INVALID_OR_EXPIRED_ONE_TIME_CODE, HttpResponseCode::unprocessableEntity(), $previous);
    }

    public function getErrors(): array
    {
        return ['OauthException' => $this->message];
    }
}
