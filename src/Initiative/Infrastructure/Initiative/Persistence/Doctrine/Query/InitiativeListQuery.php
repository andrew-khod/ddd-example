<?php

declare(strict_types=1);

namespace App\Initiative\Infrastructure\Initiative\Persistence\Doctrine\Query;

use App\Identity\Domain\Customer\BannedCustomer;
use App\Initiative\Application\Initiative\Query\InitiativeByFilterCriteria;
use App\Initiative\Domain\Category\CategoryId;
use App\Initiative\Domain\Initiative\Initiative;
use App\Initiative\Domain\Initiative\InitiativeCollection;
use App\Shared\Infrastructure\Doctrine\Query\SwitchableTenantBaseQuery;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\Query\Expr\OrderBy;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

class InitiativeListQuery extends SwitchableTenantBaseQuery
{
    protected function getClass(): string
    {
        return Initiative::class;
    }

//    public function queryMultiple(InitiativeCriteria $criteria): InitiativeCollection
//    public function queryMultiple(InitiativeByFilterCriteria $criteria): InitiativeCollection
    public function queryMultiple(InitiativeByFilterCriteria $criteria): array
    {
        // todo rewritten InitiativeList back from basequery; refactor this using Doctrine Specification
        $limit = 10;
        $query = $this->query($criteria);
        $queryTotal = clone $query;
        $queryTotal = $queryTotal->addSelect('COUNT(initiative.id) as total')
            ->from(Initiative::class, 'initiative')
            ->where('initiative.is_archived != true')
            ->getQuery();

        if ($criteria->isLoadFromFirstPage()) {
            $query->setFirstResult(0);
            $query->setMaxResults($criteria->page() * $limit);
        } else {
            $query->setFirstResult(($criteria->page() - 1) * $limit);
            $query->setMaxResults($limit);
        }

        $paginator = new Paginator($query);

        $count = $paginator->count();

//        // todo InitiativePaginationPayload
//        if ($criteria->isExcludeArchived() && $criteria->authenticatedCustomer()) {
//            $count = $queryTotal->getSingleResult();
//            $count = $count['total'];
//        } else {
//            $count = $paginator->count();
//        }

        return [
            'items' => new InitiativeCollection(...$paginator->getIterator()->getArrayCopy()),
            'pages' => (int) ceil($count / $limit),
            'total' => $count,
        ];
    }

    public function queryMultipleNoPagination(InitiativeByFilterCriteria $criteria): array
    {
        return $this->query($criteria)->getQuery()->getResult();
    }

    // fixme rename
    public function queryMultipleWithLocationsNoPagination(InitiativeByFilterCriteria $criteria): array
    {
        $query = $this->query($criteria);
        $noLocationQuery = clone $query;
        $query->andWhere('initiative.location IS NOT NULL');

        // fixme refactor building common filtering parts
        $noLocationQuery->andWhere('initiative.location IS NULL');
        $noLocationQuery->select('COUNT(DISTINCT initiative.id) as total');
        $noLocationQuery->resetDQLParts(['orderBy', 'groupBy']);

//        $noLocationQuery = $this->switchableActiveTenant->entityManager()->createQueryBuilder()
//            ->select('COUNT(initiative) as total')
//            ->from(Initiative::class, 'initiative')
//            ->where('initiative.location IS NULL')
        ;
//        $noLocationQuery = $this->applyFilters($noLocationQuery, $criteria);

        return [
            'items' => new InitiativeCollection(...$query->getQuery()->getResult()),
            'no_location' => $noLocationQuery->getQuery()->getSingleResult()['total'],
        ];
    }

    private function query(InitiativeByFilterCriteria $criteria): QueryBuilder
    {
        // todo rewritten InitiativeList back from basequery; refactor this using Doctrine Specification
//        $query = $this->switchableActiveTenant
//            ->entityManager()
//            ->createQueryBuilder();
        $query = $this->createQueryBuilder('initiative');
//        $queryTotal = clone $query;
//        $queryTotal = $queryTotal->addSelect('COUNT(initiative.id) as total')
//            ->from(Initiative::class, 'initiative')
//            ->where('initiative.is_archived != true')
//            ->getQuery();

        $query->addSelect(
//            'initiative',
            'author',
            'participants',
            'followers',
            'comments',
            'comment_author',
            'images',
            'categories',
        )
//            ->addSelect('initiative.date_end > CURRENT_TIMESTAMP()')
//            ->addSelect($query->expr()->gt('initiative.date_end', 'CURRENT_TIMESTAMP()'))
            ->addSelect('(CASE WHEN initiative.date_end > CURRENT_TIMESTAMP() THEN 1 ELSE 0 END) AS HIDDEN is_active')
//            ->from(Initiative::class, 'initiative')
            // todo optimize left joins to right joins and partial hydration?
            ->leftJoin('initiative.customer', 'author')
            ->leftJoin('initiative.participants', 'participants')
            ->leftJoin('initiative.followers', 'followers')
            ->leftJoin('initiative.comments', 'comments', Join::WITH, 'comments.archived_at IS NULL')
            ->leftJoin('comments.customer', 'comment_author')
//            ->leftJoin('comments.customer', 'comment_author', Join::WITH, sprintf('comment_author NOT INSTANCE OF %s', BannedCustomer::class))
            ->leftJoin('initiative.images', 'images')
            ->leftJoin('initiative.categories', 'categories')
            ->andWhere(sprintf('author NOT INSTANCE OF %s', BannedCustomer::class))
            ->andWhere(sprintf('(comment_author NOT INSTANCE OF %s OR comment_author IS NULL)', BannedCustomer::class))
            ->orderBy('is_active', Criteria::DESC)
//            ->orderBy($query->expr()->gt('initiative.date_end', 'CURRENT_TIMESTAMP()'), Criteria::DESC)
//            ->orderBy('initiative.date_end > CURRENT_TIMESTAMP()', Criteria::DESC)
        ;

        $query = $this->applyFilters($query, $criteria);

        $query->addOrderBy('comments.created', Criteria::DESC);

        return $query;
    }

    private function applyFilters(QueryBuilder $query, InitiativeByFilterCriteria $criteria): QueryBuilder
    {
        $filter = $criteria->filter();
        $sort = $criteria->sort();
//        $query->addSelect('(CASE WHEN is_active=1 THEN initiative.date_start ELSE NULLIF(1,1) END) AS HIDDEN order_active');
//        $query->addSelect('(CASE WHEN is_active=0 THEN initiative.date_start ELSE NULLIF(1,1) END) AS HIDDEN order_inactive');
//        $query->addOrderBy('order_active', Criteria::ASC);
//        $query->addOrderBy('order_inactive', Criteria::DESC);

        switch ($sort) {
            case 'title':
                $query->addOrderBy('initiative.title', Criteria::ASC);
                break;
            case 'description':
                $query->addOrderBy('initiative.description', Criteria::ASC);
                break;
            case 'joined':
                //todo order we finally get is a bit wrong because of non unique rows with joined relations
                //todo so try KnpPaginator sorting and think about it in general
                //todo alternatively use nested Select query
                $subQuery = $this->switchableActiveTenant
                    ->entityManager()
                    ->createQueryBuilder()
                    ->addSelect('participation')
//                    ->addSelect($query->expr()->count('participants.id'))
                    ->from('participation', 'participation')
//                    ->where('initiative_id = :initiative')
                ;
//                $query->addSelect($subQuery->getQuery());

//                $query->addSelect('COUNT(participants.id) AS HIDDEN joined');
//                $query->addOrderBy('joined', Criteria::DESC);
//                $query->groupBy(
//                    'initiative',
//                    'author',
//                    'participants',
//                    'comments',
//                    'images',
//                    'categories',
//                );
                break;
            case 'created':
            default:
                $query->addOrderBy('initiative.created', Criteria::DESC);
                $query->addOrderBy(new OrderBy('CASE WHEN is_active=1 THEN initiative.date_start ELSE NULLIF(1,1) END', Criteria::ASC));
                $query->addOrderBy(new OrderBy('CASE WHEN is_active=0 THEN initiative.date_start ELSE NULLIF(1,1) END', Criteria::DESC));
                break;
        }

        if (key_exists('categories', $filter)) {
            $categories = array_map(fn(string $id) => (new CategoryId($id))->toBinary(), $filter['categories']);
//            $query->andWhere($query->expr()->in('categories.id', $categories));
            $query->andWhere('categories.id IN (:categories)');
            $query->setParameter('categories', $categories);
        }

        if (key_exists('text', $filter)) {
            $query->andWhere('initiative.title LIKE :search OR initiative.description LIKE :search');
            $query->setParameter('search', sprintf('%%%s%%', $filter['text']));
        }

        if (key_exists('date_start', $filter)) {
            $query->andWhere('initiative.date_start >= :date_from');
            $query->setParameter('date_from', $filter['date_start']);
        }

        if (key_exists('date_end', $filter)) {
            $query->andWhere('initiative.date_start <= :date_to');
            $query->setParameter('date_to', $filter['date_end']);
        }

        if ($criteria->isExcludeArchived()) {
//            if ($criteria->authenticatedCustomer()) {
//                $query->andWhere('initiative.is_archived != true OR author.id = :author');
//                $query->setParameter(':author', $criteria->authenticatedCustomer()->id()->toBinary());
//            } else {
                $query->andWhere('initiative.is_archived != true');
//            }
        }

        return $query;
    }
}
