<?php

declare(strict_types=1);

namespace App\Customization\Infrastructure;

use App\Customization\Application\RuleEntityManager as RuleEntityManagerInterface;
use App\Customization\Domain\Rule;
use App\Identity\Infrastructure\Company\SwitchableActiveTenant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

class RuleEntityManager extends ServiceEntityRepository implements RuleEntityManagerInterface
{
    private EntityManagerInterface $entityManager;

//    public function __construct(ManagerRegistry $registry, SwitchableActiveTenant $activeTenant, string $class)
    public function __construct(ManagerRegistry $registry, SwitchableActiveTenant $activeTenant)
    {
        parent::__construct($registry, Rule::class);

        $this->entityManager = $activeTenant->entityManager();
    }

    public function create(Rule $rule): void
    {
        $this->entityManager->persist($rule);
    }

    public function flush(): void
    {
        $this->entityManager->flush();
    }

    public function deleteAll(): void
    {
        $this->entityManager
            ->createQueryBuilder()
            ->delete(Rule::class, 'r')
            ->getQuery()
            ->getResult();
    }
}
