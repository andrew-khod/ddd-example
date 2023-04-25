<?php

declare(strict_types=1);

namespace App\Identity\Infrastructure\Customer\Persistence\Doctrine;

use App\Identity\Application\Customer\CustomerAlreadyExistException;
use App\Identity\Application\Customer\CustomerEntityManager as CustomerEntityManagerInterface;
use App\Identity\Application\User\Exception\UserException;
use App\Identity\Domain\Customer\BaseCustomer;
use App\Identity\Domain\Customer\Customer;
use App\Identity\Domain\Customer\CustomerId;
use App\Identity\Infrastructure\Company\SwitchableActiveTenant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\UuidV4;

final class CustomerEntityManager extends ServiceEntityRepository implements CustomerEntityManagerInterface
{
    private EntityManagerInterface $entityManager;

//    public function __construct(ManagerRegistry $registry, ActiveTenant $activeTenant)
    public function __construct(ManagerRegistry $registry, SwitchableActiveTenant $activeTenant)
    {
        parent::__construct($registry, Customer::class);

        $this->registry = $registry;
        $this->entityManager = $activeTenant->entityManager();
    }

    public function create(BaseCustomer $customer): void
    {
        $this->entityManager->persist($customer);

        // TODO get rid of this try/catch block because we throw it in this::update
        try {
            $this->update();
        } catch (UniqueConstraintViolationException $exception) {
            throw UserException::userAlreadyExist();
        }
    }

    public function update(): void
    {
        try {
            $this->entityManager->flush();
        } catch (UniqueConstraintViolationException $exception) {
            throw new CustomerAlreadyExistException();
//            throw new EntityAlreadyExistException();
        }
    }

    public function nextId(): CustomerId
    {
        return new CustomerId((new UuidV4())->toRfc4122());
    }

    public function updateInheritanceType($entity, string $type): void
    {
        // TODO find more elegant way to update discriminator column for inherited mappings
        // TODO ref: https://stackoverflow.com/questions/67639250/doctrine2-bounded-contexts-of-the-entity-and-single-table-inheritance-mapping
        /* TODO ref:
           I’m assuming you’re experimenting/playing with DDD and bounded contexts.
           At a quick look, I would say that saying that NotActivatedCustomer, DeletedCustomer, and Customer are 3 different bounded contexts is kind of a big statement. But I won’t dispute the modelling itself.
           Single table inheritance won’t work for what you want to achieve it’s a orm feature thought for another purpose (things don’t usually change from one type to another, and if so, this is through a DELETE+INSERT).
           Explore multiple entity managers, where each entity manager belongs to a specific context, that would work better.
           ps: each bounded context is a silos, so when things are interacting, there is something off in the modelling, if your application changes a state from one to another, that state is something belonging to the same context. At most you use events or similar to communicate between bounded contexts (but you still don’t need it for the specific example) (edited)
         */
        $conn = $this->entityManager->getConnection();
        $conn->executeStatement('UPDATE customer SET discr = ? WHERE id = ?', [$type, $entity->id()->toBinary()]);
    }

    // fixme think about deprecated merge option
    public function sync(object $entity): object
    {
        return $this->entityManager->merge($entity);
    }
}
