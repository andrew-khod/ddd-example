<?php

declare(strict_types=1);

namespace App\Identity\Infrastructure\AuthVendor\Persistence\Doctrine\Query;

use App\Identity\Application\AuthVendor\Query\AuthVendorByCriteriaQuery as AuthVendorByCriteriaQueryInterface;
use App\Identity\Application\AuthVendor\Query\AuthVendorCriteria;
use App\Identity\Domain\AuthVendor\AuthVendor;
use App\Identity\Domain\AuthVendor\AuthVendorCollection;
use App\Shared\Infrastructure\Doctrine\Query\BaseQuery;

final class AuthVendorByCriteriaQuery extends BaseQuery implements AuthVendorByCriteriaQueryInterface
{
    protected function getClass(): string
    {
        return AuthVendor::class;
    }

    public function queryOne(AuthVendorCriteria $criteria): ?AuthVendor
    {
        return $this->findOneByCriteria($criteria);
    }

    public function queryMultiple(AuthVendorCriteria $criteria): AuthVendorCollection
    {
        return new AuthVendorCollection(...$this->findMultipleByCriteria($criteria));
    }
}
