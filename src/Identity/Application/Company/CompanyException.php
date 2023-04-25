<?php

declare(strict_types=1);

namespace App\Identity\Application\Company;

use App\Shared\Application\HttpResponseCode;
use App\Shared\Domain\BaseException;
use DomainException;

final class CompanyException extends DomainException implements BaseException
{
    public static function companyAlreadyExist(): self
    {
        return new self('Company already exist', HttpResponseCode::conflict());
    }

    public static function companyNotFound(): self
    {
        return new self('Company not found', HttpResponseCode::notFound());
    }

    public static function appKeyEmpty(): self
    {
        return new self('No X-App-Key header nor APP_COMPANY_KEY env provided', HttpResponseCode::badRequest());
    }

    public function getErrors(): array
    {
        return ['CompanyException' => $this->message];
    }
}
