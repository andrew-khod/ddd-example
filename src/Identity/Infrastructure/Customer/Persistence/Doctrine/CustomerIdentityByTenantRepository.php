<?php

namespace App\Identity\Infrastructure\Customer\Persistence\Doctrine;

use App\Identity\Domain\Customer\Customer;
use App\Identity\Infrastructure\Company\SwitchableActiveTenant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;

class CustomerIdentityByTenantRepository extends ServiceEntityRepository implements UserLoaderInterface
{
    public function __construct(ManagerRegistry $registry,
                                SwitchableActiveTenant $activeTenant,
//                                ActiveTenant $activeTenant,
                                string $customerEntitySubTypeClass = null)
    {
        parent::__construct($registry, $customerEntitySubTypeClass ?? Customer::class);

        $this->_em = $activeTenant->entityManager();
    }

    public function loadUserByUsername(string $username)
    {
        // TODO
    }

    public function findOneBy(array $criteria, array $orderBy = null)
    {
        return $this->createQueryBuilder('customer')
            ->addSelect('following')
            ->addSelect('initiative')
            ->addSelect('author')
            ->leftJoin('customer.following', 'following')
            ->leftJoin('following.initiative', 'initiative')
            ->leftJoin('initiative.customer', 'author')
            ->addOrderBy('following.created', 'desc')
            ->where(sprintf('customer.%s=:val', key($criteria)))
            ->setParameter('val', $criteria[key($criteria)])
            ->getQuery()->getOneOrNullResult();

//        return parent::findOneBy($criteria, $orderBy);

//        // todo here is a partial hydration going on, used to avoid the case when
//        // you inject AuthenticatedCustomer/AuthenticatedUser and $em->filters() are unknown, whenever they turned on or off.
//        // maybe think about a better way to avoid increased memory usage
//        /** @var Customer $customer */
//        $customer = $this->createQueryBuilder('customer')
//            ->addSelect('participation')
//            ->addSelect('categories')
//            ->addSelect('translations')
//            ->addSelect('language')
//            ->addSelect('comments')
//            ->addSelect('participation_comments')
//            ->addSelect('images')
//            ->addSelect('followers')
//            ->addSelect('participants')
//            ->leftJoin('customer.comments', 'comments')
//            ->leftJoin('customer.participation', 'participation')
//            ->leftJoin('participation.images', 'images')
//            ->leftJoin('participation.followers', 'followers')
//            ->leftJoin('participation.participants', 'participants')
//            ->leftJoin('participation.comments', 'participation_comments')
//            ->leftJoin('participation.categories', 'categories')
//            ->leftJoin('categories.translations', 'translations')
//            ->leftJoin('translations.language', 'language')
//            ->where(sprintf('customer.%s=:val', key($criteria)))
//            ->addOrderBy('participation_comments.created', 'desc')
//            ->setParameter('val', $criteria[key($criteria)])
//            ->getQuery()->getSingleResult();
//        ;
//
//        $this->createQueryBuilder('customer')
//            ->addSelect('following')
//            ->addSelect('categories')
//            ->addSelect('translations')
//            ->addSelect('language')
//            ->addSelect('following_comments')
//            ->addSelect('images')
//            ->addSelect('followers')
//            ->addSelect('participants')
//            ->leftJoin('customer.following', 'following')
//            ->leftJoin('following.images', 'images')
//            ->leftJoin('following.followers', 'followers')
//            ->leftJoin('following.participants', 'participants')
//            ->leftJoin('following.comments', 'following_comments')
//            ->leftJoin('following.categories', 'categories')
//            ->leftJoin('categories.translations', 'translations')
//            ->leftJoin('translations.language', 'language')
//            ->where('customer.id = :id')
//            ->addOrderBy('following_comments.created', 'desc')
//            ->setParameter('id', $customer->id()->toBinary())
//            ->getQuery()->getSingleResult();
//
//        $this->createQueryBuilder('customer')
//            ->addSelect('initiatives')
//            ->addSelect('categories')
//            ->addSelect('translations')
//            ->addSelect('language')
//            ->addSelect('initiatives_comments')
//            ->addSelect('images')
//            ->addSelect('followers')
//            ->addSelect('participants')
//            ->leftJoin('customer.initiatives', 'initiatives')
//            ->leftJoin('initiatives.images', 'images')
//            ->leftJoin('initiatives.followers', 'followers')
//            ->leftJoin('initiatives.participants', 'participants')
//            ->leftJoin('initiatives.comments', 'initiatives_comments')
//            ->leftJoin('initiatives.categories', 'categories')
//            ->leftJoin('categories.translations', 'translations')
//            ->leftJoin('translations.language', 'language')
//            ->where('customer.id = :id')
//            ->addOrderBy('initiatives_comments.created', 'desc')
//            ->setParameter('id', $customer->id()->toBinary())
//            ->getQuery()->getSingleResult();
//        ;
//
//        $this->createQueryBuilder('customer')
//            ->addSelect('favourites')
//            ->addSelect('categories')
//            ->addSelect('translations')
//            ->addSelect('language')
//            ->addSelect('favourites_comments')
//            ->addSelect('images')
//            ->addSelect('followers')
//            ->addSelect('participants')
//            ->leftJoin('customer.favourites', 'favourites')
//            ->leftJoin('favourites.images', 'images')
//            ->leftJoin('favourites.followers', 'followers')
//            ->leftJoin('favourites.participants', 'participants')
//            ->leftJoin('favourites.comments', 'favourites_comments')
//            ->leftJoin('favourites.categories', 'categories')
//            ->leftJoin('categories.translations', 'translations')
//            ->leftJoin('translations.language', 'language')
//            ->where('customer.id = :id')
//            ->addOrderBy('favourites_comments.created', 'desc')
//            ->setParameter('id', $customer->id()->toBinary())
//            ->getQuery()->getSingleResult();
//        ;
//
//        return $customer;

//        return $this->createQueryBuilder('customer')
////            ->addSelect('comments')
////            ->addSelect('initiatives_comments')
////            ->addSelect('participation_initiatives_comments')
////            ->addSelect('following_initiatives_comments')
////            ->addSelect('favourites_initiatives_comments')
//
//            ->addSelect('participation')
////                ->addSelect('categories_p1')
////                    ->addSelect('ct_1')
////                        ->addSelect('l_1')
//
//            ->addSelect('following')
////                ->addSelect('categories_f1')
////                    ->addSelect('ct_2')
////                        ->addSelect('l_2')
//
//            ->addSelect('initiatives')
////                ->addSelect('categories_i1')
////                    ->addSelect('ct_3')
////                        ->addSelect('l_3')
//
//            ->addSelect('favourites')
////                ->addSelect('categories_f2')
////                    ->addSelect('ct_4')
////                        ->addSelect('l_4')
//
////                    ->addSelect('categories_f1')
////                    ->addSelect('categories_i1')
////                    ->addSelect('categories_f2')
//
//            ->leftJoin('customer.participation', 'participation')
//                ->join('participation.categories', 'categories_p1')
//                    ->join('categories_p1.translations', 'ct_1')
//                        ->join('ct_1.language', 'l_1')
//            ->leftJoin('customer.following', 'following')
//                ->join('following.categories', 'categories_f1')
//                    ->join('categories_f1.translations', 'ct_2')
//                        ->join('ct_2.language', 'l_2')
//            ->leftJoin('customer.initiatives', 'initiatives')
////                ->join('following.categories', 'categories_i1')
////                    ->join('categories_i1.translations', 'ct_3')
////                        ->join('ct_3.language', 'l_3')
//            ->leftJoin('customer.favourites', 'favourites')
////                ->join('following.categories', 'categories_f2')
////                    ->join('categories_f2.translations', 'ct_4')
////                        ->join('ct_4.language', 'l_4')
//
////                    ->join('following.categories', 'categories_f1')
////                    ->join('initiatives.categories', 'categories_i1')
////                    ->join('favourites.categories', 'categories_f2')
//
////            ->leftJoin('customer.comments', 'comments')
////            ->leftJoin('initiatives.comments', 'initiatives_comments')
////            ->leftJoin('participation.comments', 'participation_initiatives_comments')
////            ->leftJoin('following.comments', 'following_initiatives_comments')
////            ->leftJoin('favourites.comments', 'favourites_initiatives_comments')
//            ->where(sprintf('customer.%s=:val', key($criteria)))
//            ->setParameter('val', $criteria[key($criteria)])
//            ->getQuery()
//            ->getSingleResult();
    }
}
