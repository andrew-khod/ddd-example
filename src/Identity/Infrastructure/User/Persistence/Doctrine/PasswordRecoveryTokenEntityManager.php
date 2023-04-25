<?php

declare(strict_types=1);

namespace App\Identity\Infrastructure\User\Persistence\Doctrine;

use App\Identity\Application\User\PasswordRecoveryTokenEntityManager as PasswordRecoveryTokenEntityManagerInterface;
use App\Identity\Domain\PasswordRecoveryToken\PasswordRecoveryToken;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class PasswordRecoveryTokenEntityManager extends ServiceEntityRepository implements PasswordRecoveryTokenEntityManagerInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PasswordRecoveryToken::class);
    }

    public function save(PasswordRecoveryToken $token): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($token);
        $entityManager->flush();
    }

    public function delete(PasswordRecoveryToken $token): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->remove($token);
        $entityManager->flush();
    }
}
