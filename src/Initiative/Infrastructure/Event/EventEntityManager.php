<?php

declare(strict_types=1);

namespace App\Initiative\Infrastructure\Event;

use App\Identity\Infrastructure\Company\SwitchableActiveTenant;
use App\Initiative\Application\Event\EventEntityManager as EventEntityManagerAlias;
use App\Initiative\Domain\Category\Category;
use App\Initiative\Domain\Event\Event;
use App\Shared\Application\EntityAlreadyExistException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

final class EventEntityManager extends ServiceEntityRepository implements EventEntityManagerAlias
{
    private EntityManagerInterface $entityManager;

    public function __construct(ManagerRegistry $registry, SwitchableActiveTenant $activeTenant)
    {
        parent::__construct($registry, Category::class);

        $this->entityManager = $activeTenant->entityManager();
    }

    public function create(Event ...$events): void
    {
        foreach ($events as $event) {
            $this->entityManager->persist($event);
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