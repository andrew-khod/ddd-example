<?php

declare(strict_types=1);

namespace App\Identity\Infrastructure\Company\Persistence\Doctrine;

use App\Identity\Application\Company\CompanyEntityManager as CompanyEntityManagerInterface;
use App\Identity\Application\Company\CompanyException;
use App\Identity\Application\User\Exception\UserException;
use App\Identity\Domain\Company\Company;
use App\Identity\Domain\Company\CompanyId;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\UuidV4;

final class CompanyEntityManager extends ServiceEntityRepository implements CompanyEntityManagerInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Company::class);
    }

    public function save(Company $company): void
    {
        $entityManager = $this->getEntityManager();

        try {
            $entityManager->persist($company);
            $entityManager->flush();
        } catch (UniqueConstraintViolationException $exception) {
            throw CompanyException::companyAlreadyExist();
        }
    }

    public function update(): void
    {
        try {
            $this->getEntityManager()->flush();
        } catch (UniqueConstraintViolationException $exception) {
            throw UserException::userAlreadyExist();
        }
    }

    public function nextId(): CompanyId
    {
        return new CompanyId((new UuidV4())->toRfc4122());
    }
}
