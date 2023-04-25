<?php

declare(strict_types=1);

namespace App\Customization\Infrastructure;

use App\Customization\Application\PrivacyPolicyEntityManager as PrivacyPolicyEntityManagerAlias;
use App\Customization\Domain\PrivacyPolicy;
use App\Identity\Infrastructure\Company\SwitchableActiveTenant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

class PrivacyPolicyEntityManager extends ServiceEntityRepository implements PrivacyPolicyEntityManagerAlias
{
    private EntityManagerInterface $entityManager;

//    public function __construct(ManagerRegistry $registry, SwitchableActiveTenant $activeTenant, string $class)
    public function __construct(ManagerRegistry $registry, SwitchableActiveTenant $activeTenant)
    {
        parent::__construct($registry, PrivacyPolicy::class);

        $this->entityManager = $activeTenant->entityManager();
    }

    public function create(PrivacyPolicy $policy): void
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
            ->delete(PrivacyPolicy::class, 'p')
            ->getQuery()
            ->getResult();
    }
}
