<?php

declare(strict_types=1);

namespace App\Shared\Application;

use App\Shared\Domain\BaseException;
use Exception;
use Throwable;

class EntityAlreadyExistException extends Exception implements BaseException
{
    public function __construct($message = '', $code = 0, Throwable $previous = null)
    {
        parent::__construct('Entity already exist', HttpResponseCode::conflict(), $previous);
    }

    public function getErrors(): array
    {
        return ['EntityException' => $this->message];
    }
}