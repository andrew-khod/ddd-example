<?php

declare(strict_types=1);

namespace App\Identity\Infrastructure\Customer\Persistence\Doctrine\Query;

use App\Identity\Domain\Customer\Customer;
use App\Identity\Domain\Customer\CustomerId;
use App\Shared\Infrastructure\Doctrine\Query\SwitchableTenantBaseQuery;
use Doctrine\ORM\Query\Expr\Join;

final class CustomerForStatisticsQuery extends SwitchableTenantBaseQuery
{
    private $relations = [
        'participation',
//        'following',
        'initiatives',
        'favourites'
    ];

    public function query(CustomerId $id): ?Customer
    {
        $id = $id->toBinary();

        // todo here is a partial hydration going on, used to avoid the case when
        // you inject AuthenticatedCustomer/AuthenticatedUser and $em->filters() are unknown, whenever they turned on or off.
        // maybe think about a better way to avoid increased memory usage
        // todo optimize queries by splitting to multiple standalone ones instead of a lot of joins
        /** @var Customer $customer */
        $customer = $this->createQueryBuilder('customer')
            ->andWhere('customer.id=:id')
            ->setParameter('id', $id)
            ->getQuery()->getOneOrNullResult();
//            ->getQuery()->getSingleResult(); // todo getOneOrNullResult

        if (!$customer) {
            return null;
        }

        $this->createQueryBuilder('customer')
            ->addSelect('comments')
            ->leftJoin('customer.comments', 'comments')
            ->andWhere('customer.id=:id')
            ->addOrderBy('comments.created', 'desc')
            ->setParameter('id', $id)
            ->getQuery()->getResult();

        $this->createQueryBuilder('customer')
            ->addSelect('following')
            ->addSelect('initiative')
            ->leftJoin('customer.following', 'following')
            ->leftJoin('following.initiative', 'initiative')
            ->addOrderBy('following.created', 'desc')
            ->andWhere('customer.id=:id')
            ->setParameter('id', $id)
            ->getQuery()->getResult();

        $this->createQueryBuilder('customer')
            ->addSelect('following')
            ->addSelect('initiative')
            ->addSelect('images')
            ->leftJoin('customer.following', 'following')
            ->leftJoin('following.initiative', 'initiative')
            ->leftJoin('initiative.images', 'images')
            ->andWhere('customer.id=:id')
            ->setParameter('id', $id)
            ->getQuery()->getResult();

        $this->createQueryBuilder('customer')
            ->addSelect('following')
            ->addSelect('initiative')
            ->addSelect('followers')
            ->leftJoin('customer.following', 'following')
            ->leftJoin('following.initiative', 'initiative')
            ->leftJoin('initiative.followers', 'followers')
            ->andWhere('customer.id=:id')
            ->setParameter('id', $id)
            ->getQuery()->getResult();

        $this->createQueryBuilder('customer')
            ->addSelect('following')
            ->addSelect('initiative')
            ->addSelect('participants')
            ->leftJoin('customer.following', 'following')
            ->leftJoin('following.initiative', 'initiative')
            ->leftJoin('initiative.participants', 'participants')
            ->andWhere('customer.id=:id')
            ->setParameter('id', $id)
            ->getQuery()->getResult();

        $this->createQueryBuilder('customer')
            ->addSelect('following')
            ->addSelect('initiative')
            ->addSelect('comments')
            ->leftJoin('customer.following', 'following')
            ->leftJoin('following.initiative', 'initiative')
            ->leftJoin('initiative.comments', 'comments')
            ->andWhere('customer.id=:id')
            ->addOrderBy('comments.created', 'desc')
            ->setParameter('id', $id)
            ->getQuery()->getResult();

        $this->createQueryBuilder('customer')
            ->addSelect('following')
            ->addSelect('initiative')
            ->addSelect('categories')
            ->addSelect('translations')
            ->addSelect('language')
            ->leftJoin('customer.following', 'following')
            ->leftJoin('following.initiative', 'initiative')
            ->leftJoin('initiative.categories', 'categories')
            ->leftJoin('categories.translations', 'translations')
            ->leftJoin('translations.language', 'language')
            ->andWhere('customer.id=:id')
            ->setParameter('id', $id)
            ->getQuery()->getResult();

        foreach ($this->relations as $relation) {
            $this->createQueryBuilder('customer')
                ->addSelect('relation')
                ->leftJoin(sprintf('customer.%s', $relation), 'relation', Join::WITH, 'relation.is_archived != TRUE OR relation.customer = :id')
                ->addOrderBy('relation.created', 'desc')
                ->andWhere('customer.id=:id')
                ->setParameter('id', $id)
                ->getQuery()->getResult();

            $this->createQueryBuilder('customer')
                ->addSelect('images')
                ->addSelect('relation')
                ->leftJoin(sprintf('customer.%s', $relation), 'relation', Join::WITH, 'relation.is_archived != TRUE OR relation.customer = :id')
                ->leftJoin('relation.images', 'images')
                ->andWhere('customer.id=:id')
                ->setParameter('id', $id)
                ->getQuery()->getResult();

            $this->createQueryBuilder('customer')
                ->addSelect('followers')
                ->addSelect('relation')
                ->leftJoin(sprintf('customer.%s', $relation), 'relation', Join::WITH, 'relation.is_archived != TRUE OR relation.customer = :id')
                ->leftJoin('relation.followers', 'followers')
                ->andWhere('customer.id=:id')
                ->setParameter('id', $id)
                ->getQuery()->getResult();

            $this->createQueryBuilder('customer')
                ->addSelect('participants')
                ->addSelect('relation')
                ->leftJoin(sprintf('customer.%s', $relation), 'relation', Join::WITH, 'relation.is_archived != TRUE OR relation.customer = :id')
                ->leftJoin('relation.participants', 'participants')
                ->andWhere('customer.id=:id')
                ->setParameter('id', $id)
                ->getQuery()->getResult();

            $this->createQueryBuilder('customer')
                ->addSelect('comments')
                ->addSelect('relation')
                ->leftJoin(sprintf('customer.%s', $relation), 'relation', Join::WITH, 'relation.is_archived != TRUE OR relation.customer = :id')
                ->leftJoin('relation.comments', 'comments')
                ->andWhere('customer.id=:id')
                ->addOrderBy('comments.created', 'desc')
                ->setParameter('id', $id)
                ->getQuery()->getResult();

            $this->createQueryBuilder('customer')
                ->addSelect('categories')
                ->addSelect('translations')
                ->addSelect('language')
                ->addSelect('relation')
                ->leftJoin(sprintf('customer.%s', $relation), 'relation', Join::WITH, 'relation.is_archived != TRUE OR relation.customer = :id')
                ->leftJoin('relation.categories', 'categories')
                ->leftJoin('categories.translations', 'translations')
                ->leftJoin('translations.language', 'language')
                ->andWhere('customer.id=:id')
                ->setParameter('id', $id)
                ->getQuery()->getResult();
        }

        return $customer;
    }

    protected function getClass(): string
    {
        return Customer::class;
    }
}