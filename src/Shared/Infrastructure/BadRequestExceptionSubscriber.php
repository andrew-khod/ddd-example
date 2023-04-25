<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure;

use App\Shared\UI\Http\Request\BadRequestException;
use Throwable;

final class BadRequestExceptionSubscriber extends AbstractExceptionSubscriber
{
    public static function getSubscribedEvents()
    {
        return [
            'kernel.exception' => 'onKernelException',
        ];
    }

    protected function supports(Throwable $exception): bool
    {
        return $exception instanceof BadRequestException;
    }

    protected function getErrors(Throwable $exception): array
    {
        $errors = [];

        foreach ($exception->getErrors() as $error) {
            $name = $error->getPropertyPath();
//            $name = trim($name, '[]');
            $errors[$name] = $error->getMessage();
        }

        return $errors;
    }
}
