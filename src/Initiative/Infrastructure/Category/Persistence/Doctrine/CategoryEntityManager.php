<?php

declare(strict_types=1);

namespace App\Initiative\Infrastructure\Category\Persistence\Doctrine;

use App\Identity\Infrastructure\Company\SwitchableActiveTenant;
use App\Initiative\Application\Category\CategoryEntityManager as CategoryEntityManagerAlias;
use App\Initiative\Domain\Category\Category;
use App\Initiative\Domain\Category\CategoryId;
use App\Shared\Application\EntityAlreadyExistException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\UuidV4;

class CategoryEntityManager extends ServiceEntityRepository implements CategoryEntityManagerAlias
{
    private EntityManagerInterface $entityManager;

    public function __construct(ManagerRegistry $registry, SwitchableActiveTenant $activeTenant)
    {
        parent::__construct($registry, Category::class);

        $this->entityManager = $activeTenant->entityManager();
    }

    public function create(Category ...$categories): void
    {
        foreach ($categories as $category) {
            $this->entityManager->persist($category);
        }

//        // TODO get rid of this try/catch block because we throw it in this::update
//        try {
//            $this->update();
//        } catch (UniqueConstraintViolationException $exception) {
//            throw UserException::userAlreadyExist();
//        }
    }

    public function persist(): void
    {
        try {
            $this->entityManager->flush();
        } catch (UniqueConstraintViolationException $exception) {
            throw new EntityAlreadyExistException();
        }
    }

    public function nextId(): CategoryId
    {
        return new CategoryId((new UuidV4())->toRfc4122());
    }

    public function remove(Category $category): void
    {
        $this->entityManager->remove($category);
    }
}
