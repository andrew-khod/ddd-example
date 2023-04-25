<?php

namespace App\Shared\Application;

use App\Shared\Domain\BaseException;
use Exception;
use Throwable;

class LanguageNotExistException extends Exception implements BaseException
{
    public function __construct($message = '', $code = 0, Throwable $previous = null)
    {
        parent::__construct('Language not exists', HttpResponseCode::notFound(), $previous);
    }

    public function getErrors(): array
    {
        return ['LanguageException' => $this->message];
    }
}