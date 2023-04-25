<?php

declare(strict_types=1);

namespace App\Customization\Infrastructure;

use App\Customization\Application\TermsOfUseEntityManager as TermsOfUseEntityManagerAlias;
use App\Customization\Domain\TermsOfUse;
use App\Identity\Infrastructure\Company\SwitchableActiveTenant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

class TermsOfUseEntityManager extends ServiceEntityRepository implements TermsOfUseEntityManagerAlias
{
    private EntityManagerInterface $entityManager;

//    public function __construct(ManagerRegistry $registry, SwitchableActiveTenant $activeTenant, string $class)
    public function __construct(ManagerRegistry $registry, SwitchableActiveTenant $activeTenant)
    {
        parent::__construct($registry, TermsOfUse::class);

        $this->entityManager = $activeTenant->entityManager();
    }

    public function create(TermsOfUse $policy): void
    {
        $this->entityManager->persist($policy);
    }

    public function flush(): void
    {
        $this->entityManager->flush();
    }

    public function deleteAll(): void
    {
        $this->entityManager
            ->createQueryBuilder()
            ->delete(TermsOfUse::class, 'p')
            ->getQuery()
            ->getResult();
    }
}
