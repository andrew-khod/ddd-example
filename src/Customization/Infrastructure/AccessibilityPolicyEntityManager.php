<?php

declare(strict_types=1);

namespace App\Customization\Infrastructure;

use App\Customization\Application\AccessibilityPolicyEntityManager as AccessibilityPolicyEntityManagerAlias;
use App\Customization\Domain\AccessibilityPolicy;
use App\Identity\Infrastructure\Company\SwitchableActiveTenant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

class AccessibilityPolicyEntityManager extends ServiceEntityRepository implements AccessibilityPolicyEntityManagerAlias
{
    private EntityManagerInterface $entityManager;

//    public function __construct(ManagerRegistry $registry, SwitchableActiveTenant $activeTenant, string $class)
    public function __construct(ManagerRegistry $registry, SwitchableActiveTenant $activeTenant)
    {
        parent::__construct($registry, AccessibilityPolicy::class);

        $this->entityManager = $activeTenant->entityManager();
    }

    public function create(AccessibilityPolicy $policy): void
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
            ->delete(AccessibilityPolicy::class, 'p')
            ->getQuery()
            ->getResult();
    }
}
