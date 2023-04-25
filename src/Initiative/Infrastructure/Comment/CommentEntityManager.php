<?php

declare(strict_types=1);

namespace App\Initiative\Infrastructure\Comment;

use App\Identity\Infrastructure\Company\SwitchableActiveTenant;
use App\Initiative\Application\Comment\CommentEntityManager as CommentEntityManagerInterface;
use App\Initiative\Domain\Comment\Comment;
use App\Shared\Application\EntityAlreadyExistException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

class CommentEntityManager extends ServiceEntityRepository implements CommentEntityManagerInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(ManagerRegistry $registry, SwitchableActiveTenant $activeTenant)
    {
        parent::__construct($registry, Comment::class);

        $this->entityManager = $activeTenant->entityManager();
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
