<?php

namespace App\Shared\Domain\PreUploadedImage;

use App\Shared\Application\HttpResponseCode;
use App\Shared\Domain\BaseException;
use Exception;
use Throwable;

class NotValidImageException extends Exception implements BaseException
{
    public function __construct($message = '', $code = 0, Throwable $previous = null)
    {
        parent::__construct('Not valid image', HttpResponseCode::badRequest(), $previous);
    }

    public function getErrors(): array
    {
        return ['ImageException' => $this->message];
    }
}