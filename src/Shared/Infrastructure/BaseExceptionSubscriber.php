<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure;

use App\Shared\Domain\BaseException;
use Throwable;

final class BaseExceptionSubscriber extends AbstractExceptionSubscriber
{
    protected function supports(Throwable $exception): bool
    {
        return $exception instanceof BaseException;
    }

    protected function getErrors(Throwable $exception): array
    {
        return $exception->getErrors();
    }
}
