<?php

declare(strict_types=1);

namespace App\Identity\Domain\Customer;

use App\Shared\Application\HttpResponseCode;
use App\Shared\Domain\BaseException;
use Exception;
use Throwable;

final class InvalidGenderException extends Exception implements BaseException
{
    public function __construct($message = '', $code = 0, Throwable $previous = null)
    {
        parent::__construct(CustomerTranslation::INVALID_GENDER, HttpResponseCode::unprocessableEntity(), $previous);
    }

    public function getErrors(): array
    {
        return ['InvalidGenderException' => $this->message];
    }
}
