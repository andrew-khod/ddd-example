<?php

declare(strict_types=1);

namespace App\Identity\Infrastructure\AuthVendor\Persistence\Doctrine;

use App\Identity\Application\AuthVendor\AuthVendorEntityManager as AuthVendorEntityManagerInterface;
use App\Identity\Application\User\Exception\UserException;
use App\Identity\Domain\AuthVendor\AuthVendor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\Persistence\ManagerRegistry;

final class AuthVendorEntityManager extends ServiceEntityRepository implements AuthVendorEntityManagerInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AuthVendor::class);
    }

    public function save(AuthVendor $authVendor): void
    {
        $entityManager = $this->getEntityManager();

        try {
            $entityManager->persist($authVendor);
            $entityManager->flush();
        } catch (UniqueConstraintViolationException $exception) {
            throw UserException::authVendorAlreadyExist();
        }
    }
}
