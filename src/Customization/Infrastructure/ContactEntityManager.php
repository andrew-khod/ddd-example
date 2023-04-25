<?php

declare(strict_types=1);

namespace App\Customization\Infrastructure;

use App\Customization\Application\ContactEntityManager as ContactEntityManagerInterface;
use App\Customization\Domain\Contact;
use App\Identity\Infrastructure\Company\SwitchableActiveTenant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

class ContactEntityManager extends ServiceEntityRepository implements ContactEntityManagerInterface
{
    //todo extract filters
    private array $filters = [
        'softdeleteable' => true,
    ];

    private EntityManagerInterface $entityManager;

    public function __construct(ManagerRegistry $registry, SwitchableActiveTenant $activeTenant)
    {
        parent::__construct($registry, Contact::class);

        $this->entityManager = $activeTenant->entityManager();
    }

    public function deleteAll(): void
    {
        $this->entityManager
            ->createQueryBuilder()
            ->delete(Contact::class, 'c')
            ->getQuery()
//            ->setHint(Query::HINT_CUSTOM_OUTPUT_WALKER, SoftDeleteableWalker::class)
            ->getResult();
    }

    public function create(Contact $contact): void
    {
        $this->entityManager->persist($contact);
    }

    public function flush(): void
    {
        $this->entityManager->flush();
    }
}
