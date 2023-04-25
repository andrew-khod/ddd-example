<?php

declare(strict_types=1);

namespace App\Customization\UI\Web;

use App\Identity\Domain\Company\Company;

final class KnowledgePayload
{
    private Company $company;
    private array $languages;

    public function __construct(Company $company, array $languages)
    {
        $this->company = $company;
        $this->languages = $languages;
    }

    public function company(): Company
    {
        return $this->company;
    }

    public function languages(): array
    {
        return $this->languages;
    }
}