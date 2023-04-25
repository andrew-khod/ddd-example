<?php

declare(strict_types=1);

namespace App\Customization\Infrastructure;

use App\Customization\Application\CookiesPolicyEntityManager as CookiesPolicyEntityManagerAlias;
use App\Customization\Domain\CookiesPolicy;
use App\Identity\Infrastructure\Company\SwitchableActiveTenant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

class CookiesPolicyEntityManager extends ServiceEntityRepository implements CookiesPolicyEntityManagerAlias
{
    private EntityManagerInterface $entityManager;

//    public function __construct(ManagerRegistry $registry, SwitchableActiveTenant $activeTenant, string $class)
    public function __construct(ManagerRegistry $registry, SwitchableActiveTenant $activeTenant)
    {
        parent::__construct($registry, CookiesPolicy::class);

        $this->entityManager = $activeTenant->entityManager();
    }

    public function create(CookiesPolicy $policy): void
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
            ->delete(CookiesPolicy::class, 'p')
            ->getQuery()
            ->getResult();
    }
}
