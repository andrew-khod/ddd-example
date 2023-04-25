<?php

declare(strict_types=1);

namespace App\Identity\Infrastructure\User\Persistence\Doctrine;

use App\Identity\Application\Company\CompanyEntityManager;
use App\Identity\Application\Company\Query\CompanyByCriteriaQuery;
use App\Identity\Application\User\Query\UserCompanyByUserQuery;
use App\Identity\Application\User\UserEntityManager as UserEntityManagerInterface;
use App\Identity\Domain\Company\Company;
use App\Identity\Domain\User\User;
use App\Identity\Domain\User\UserId;
use App\Identity\Infrastructure\Company\SwitchableActiveTenant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Symfony\Component\Uid\UuidV4;

final class UserEntityManager extends ServiceEntityRepository implements UserEntityManagerInterface
{
    private ManagerRegistry $registry;

    private CompanyByCriteriaQuery $companyByCriteriaQuery;

    private CompanyEntityManager $companyEntityManager;

    private UserCompanyByUserQuery $userCompanyByUserQuery;

    private EntityManagerInterface $entityManager;

    private Company $activeUserCompany;

    private SwitchableActiveTenant $activeTenant;

    public function __construct(ManagerRegistry $registry,
                                CompanyEntityManager $companyEntityManager,
                                UserCompanyByUserQuery $userCompanyByUserQuery,
                                CompanyByCriteriaQuery $companyByCriteriaQuery,
                                SwitchableActiveTenant $activeTenant)
    {
        parent::__construct($registry, User::class);

        $this->registry = $registry;
        $this->companyByCriteriaQuery = $companyByCriteriaQuery;
        $this->companyEntityManager = $companyEntityManager;
        $this->userCompanyByUserQuery = $userCompanyByUserQuery;
//        $this->entityManager = $activeTenant->entityManager();
        $this->entityManager = $this->_em;
//        $this->activeUserCompany = $activeTenant->company();
        $this->activeTenant = $activeTenant;
    }

    public function update(): void
    {
//        $this->entityManager = $this->activeTenant->entityManager();
        $this->entityManager->flush();
    }

//    public function create(BaseUser $user, Company $company): void
    public function create(User $user, Company $company): void
    {
//        $company = $this->userCompanyByUserQuery->query($user);
//        $this->activeTenant->tenant($company);
//        $entityManager = $this->activeTenant->entityManager();
        $entityManager = $this->entityManager;
//        $entityManager->persist($user->activeUserCompany());

        $entityManager->persist($user);
        //        $this->entityManager->persist($user);

        try {
            $entityManager->flush();
//            $this->entityManager->flush();
        } catch (UniqueConstraintViolationException $exception) {
//            throw UserException::userAlreadyExist();
        }

        $company->addUser($user);
//        $this->activeUserCompany->addUser($user);

        // todo rewrite legacy, now we store users in the same db as company. before we stored it in a company-related db
        // TODO can't use db transactions because of multitenancy
        try {
//            $this->companyEntityManager->update();
            $this->companyEntityManager->save($company);
//            $this->companyEntityManager->save($this->activeUserCompany);
        } catch (Exception $exception) {
            $this->entityManager->remove($user);
//            $entityManager->flush();

            throw $exception;
        }
    }

    public function nextId(): UserId
    {
        return new UserId((new UuidV4())->toRfc4122());
    }

    public function delete(User $user): void
    {
        $this->entityManager->remove($user);
    }
}
