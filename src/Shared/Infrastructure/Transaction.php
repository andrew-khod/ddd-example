<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure;

use App\Shared\Application\Transaction as TransactionInterface;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

final class Transaction implements TransactionInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function handleNow(callable $callable): void
    {
        $this->entityManager->beginTransaction();

        try {
            $callable();
            $this->entityManager->commit();
        } catch (Exception $exception) {
            $this->entityManager->rollback();
            throw $exception;
        }
    }

    public function begin(): void
    {
        $this->entityManager->beginTransaction();
    }

    public function commit(callable $callable): void
    {
        $callable();
        $this->entityManager->commit();
    }

    public function rollback(): void
    {
        $this->entityManager->rollback();
    }

    public function setEntityManager($em): void
    {
        $this->entityManager = $em;
    }
}
