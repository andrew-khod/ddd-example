<?php

namespace App\Identity\Application\Customer;

use App\Shared\Application\HttpResponseCode;
use App\Shared\Domain\BaseException;
use Exception;
use Throwable;

class CustomerAlreadyExistException extends Exception implements BaseException
{
    public function __construct($message = '', $code = 0, Throwable $previous = null)
    {
        parent::__construct('Account already exists', HttpResponseCode::conflict(), $previous);
    }

    public function getErrors(): array
    {
        return ['CustomerException' => $this->message];
    }
}