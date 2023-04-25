<?php

declare(strict_types=1);

namespace App\Initiative\Infrastructure\Event;

use App\Identity\Infrastructure\Company\SwitchableActiveTenant;
use App\Initiative\Application\Event\EventReadEntityManager as EventReadEntityManagerAlias;
use App\Initiative\Domain\Category\Category;
use App\Initiative\Domain\Event\EventReadStatus;
use App\Shared\Application\EntityAlreadyExistException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

final class EventReadEntityManager extends ServiceEntityRepository implements EventReadEntityManagerAlias
{
    private EntityManagerInterface $entityManager;

    public function __construct(ManagerRegistry $registry, SwitchableActiveTenant $activeTenant)
    {
        parent::__construct($registry, Category::class);

        $this->entityManager = $activeTenant->entityManager();
    }

    public function create(EventReadStatus ...$statuses): void
    {
        foreach ($statuses as $status) {
            $this->entityManager->persist($status);
        }
    }

    public function persist(): void
    {
        try {
            $this->entityManager->flush();
        } catch (UniqueConstraintViolationException $exception) {
            throw new EntityAlreadyExistException();
        }
    }
}