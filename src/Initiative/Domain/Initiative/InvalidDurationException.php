<?php

namespace App\Initiative\Domain\Initiative;

use App\Shared\Application\HttpResponseCode;
use App\Shared\Domain\BaseException;
use Exception;

class InvalidDurationException extends Exception implements BaseException
{
    public function __construct()
    {
        parent::__construct(InitiativeTranslation::START_DATE_IS_GREATER_THAN_END_DATA, HttpResponseCode::badRequest());
    }

    public function getErrors(): array
    {
        return ['DurationException' => $this->message];
    }
}
