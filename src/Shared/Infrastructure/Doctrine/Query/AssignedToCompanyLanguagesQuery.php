<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Doctrine\Query;

use App\Shared\Application\AssignedToCompanyLanguagesQuery as AllLanguageQueryInterface;
use App\Shared\Domain\Language;
use Happyr\DoctrineSpecification\Spec;

class AssignedToCompanyLanguagesQuery extends SwitchableTenantBaseQuery implements AllLanguageQueryInterface
{
    protected array $filters = [
        'softdeleteable' => true,
    ];

    protected function getClass(): string
    {
        return Language::class;
    }

    public function query(): array
    {
        return $this->getQueryBuilder(Spec::andX())->getQuery()->getResult();
//        return $this->repository->findAll();
    }

    public function includeDeleted(): void
    {
        $this->filters['softdeleteable'] = false;
    }

//    public function excludeDeleted(): void
//    {
//        $this->filters['softdeleteable'] = true;
//    }
}