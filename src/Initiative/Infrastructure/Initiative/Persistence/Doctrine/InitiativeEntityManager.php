<?php

declare(strict_types=1);

namespace App\Initiative\Infrastructure\Initiative\Persistence\Doctrine;

use App\Identity\Infrastructure\Company\SwitchableActiveTenant;
use App\Initiative\Application\Initiative\InitiativeEntityManager as InitiativeEntityManagerInterface;
use App\Initiative\Domain\Initiative\Initiative;
use App\Initiative\Domain\Initiative\InitiativeId;
use App\Shared\Application\EntityAlreadyExistException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\UuidV4;

class InitiativeEntityManager extends ServiceEntityRepository implements InitiativeEntityManagerInterface
{
    // todo think about overriding EntityRepository::_em instead of using local property
    private EntityManagerInterface $entityManager;

//    public function __construct(ManagerRegistry $registry, ActiveTenant $activeTenant)
    public function __construct(ManagerRegistry $registry, SwitchableActiveTenant $activeTenant)
    {
        parent::__construct($registry, Initiative::class);

//        $this->registry = $registry;
        $this->entityManager = $activeTenant->entityManager();
    }

    public function create(Initiative $initiative): void
    {
        $this->entityManager->persist($initiative);

        try {
            $this->entityManager->flush();
        } catch (UniqueConstraintViolationException $exception) {
//            throw InitiativeException::initiativeAlreadyExist();
        }
    }

    public function nextId(): InitiativeId
    {
        return new InitiativeId((new UuidV4())->toRfc4122());
    }

    public function update(): void
    {
        try {
            $this->entityManager->flush();
        } catch (UniqueConstraintViolationException $exception) {
            throw new EntityAlreadyExistException();
        }
    }
}
