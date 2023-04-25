<?php

namespace App\Shared\UI\Http\Request;

use App\Shared\Domain\BaseException;
use Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Throwable;

final class BadRequestException extends Exception implements BaseException
{
    private ConstraintViolationListInterface $errors;

    public function __construct(ConstraintViolationListInterface $errors, $code = 0, Throwable $previous = null)
    {
        parent::__construct($errors, Response::HTTP_BAD_REQUEST, $previous);

        $this->errors = $errors;
    }

    public function getErrors(): ConstraintViolationListInterface
    {
        return $this->errors;
    }
}
