<?php

declare(strict_types=1);

namespace App\Customization\Infrastructure;

use App\Customization\Application\LanguageEntityManager as LanguageEntityManagerAlias;
use App\Identity\Infrastructure\Company\SwitchableActiveTenant;
use App\Shared\Domain\Language;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV4;

class LanguageEntityManager extends ServiceEntityRepository implements LanguageEntityManagerAlias
{
    private EntityManagerInterface $entityManager;

    public function __construct(ManagerRegistry $registry, SwitchableActiveTenant $activeTenant)
    {
        parent::__construct($registry, Language::class);

        $this->entityManager = $activeTenant->entityManager();
    }

    public function create(Language $language): void
    {
        $this->entityManager->persist($language);
    }

    public function persist(): void
    {
        $this->entityManager->flush();
    }

    public function delete(Language $language): void
    {
        $this->entityManager->remove($language);
    }

    public function nextId(): Uuid
    {
        return new UuidV4();
    }
}
