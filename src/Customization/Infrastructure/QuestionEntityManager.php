<?php

declare(strict_types=1);

namespace App\Customization\Infrastructure;

use App\Customization\Application\QuestionEntityManager as QuestionEntityManagerInterface;
use App\Customization\Domain\Question;
use App\Identity\Infrastructure\Company\SwitchableActiveTenant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

class QuestionEntityManager extends ServiceEntityRepository implements QuestionEntityManagerInterface
{
    //todo extract filters
    private array $filters = [
        'softdeleteable' => true,
    ];

    private EntityManagerInterface $entityManager;

    public function __construct(ManagerRegistry $registry, SwitchableActiveTenant $activeTenant)
    {
        parent::__construct($registry, Question::class);

        $this->entityManager = $activeTenant->entityManager();
    }

    public function deleteAll(): void
    {
        $this->entityManager
            ->createQueryBuilder()
            ->delete(Question::class, 'q')
            ->getQuery()
//            ->setHint(Query::HINT_CUSTOM_OUTPUT_WALKER, SoftDeleteableWalker::class)
            ->getResult();
    }

    public function create(Question $question): void
    {
        $this->entityManager->persist($question);
    }

    public function flush(): void
    {
        $this->entityManager->flush();
    }
}
