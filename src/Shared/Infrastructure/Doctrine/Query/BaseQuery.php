<?php

namespace App\Shared\Infrastructure\Doctrine\Query;

use App\Shared\Application\QueryCriteria;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Expr\Comparison;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectRepository;
use Happyr\DoctrineSpecification\Repository\EntitySpecificationRepositoryTrait;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

abstract class BaseQuery
{
    // fixme a lot of public methods exposed
    use EntitySpecificationRepositoryTrait;

    protected array $filters = [
        'softdeleteable' => false,
    ];

    // TODO remove dependency on db name
    public const ALIAS = 'default_db';

    protected ObjectRepository $repository;
    private PaginatorInterface $paginator;

    // fixme make protected or remove
    private EntityManagerInterface $entityManager;

    abstract protected function getClass(): string;

    public function __construct(ManagerRegistry $managerRegistry, PaginatorInterface $paginator)
    {
        $this->entityManager = $managerRegistry
            ->getManager();
        $this->repository = $managerRegistry
            ->getManager()
            ->getRepository($this->getClass());
        $this->paginator = $paginator;
    }

    protected function findOneByCriteria(QueryCriteria $criteria): ?object
    {
        return $this->queryBuilder($criteria)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    // FIXME return domain Collection type
    protected function findMultipleByCriteria(QueryCriteria $criteria,
                                              string $orderByField = null,
                                              string $orderByDirection = Criteria::ASC,
                                              int $page = 1,
                                              $pagination = false): array|PaginationInterface
    {
        $qb = $this->queryBuilder($criteria);

        if ($orderByField) {
            $qb->addOrderBy($orderByField, $orderByDirection);
        }

        // FIXME fast hack until Criteria::sort method will be interfaced (currently it's only for InitiativeCriteria, WIP)
//        if (method_exists($criteria, 'sort')) {
//            $rules = $criteria->sort();
//
//            foreach ($rules as $sort) {
//                if (is_array($sort) && count($sort)) {
//                    $alias = array_key_first($sort);
////                $qb->addSelect(sprintf('%s AS HIDDEN %s', $sort[$alias], $alias));
////                $qb->orderBy(sprintf('%s.%s', self::ALIAS, $alias));
////                $qb->groupBy(sprintf('%s.%s', self::ALIAS, $alias));
//                    $qb->addOrderBy($qb->expr()->count($sort[$alias]), Criteria::DESC);
////                $qb->orderBy($alias, Criteria::DESC);
//                    $qb->addGroupBy(self::ALIAS);
////                $qb->groupBy(self::ALIAS);
////                $qb->groupBy($sort[array_key_first($sort)]);
//                }
//
//                // fixme cleaning up code isn't necessary in the sake of moving to Doctrine Specification
//                if (is_string($sort)) {
//                    if (stripos($sort, '.') !== false) {
//                        $qb->addOrderBy($sort, Criteria::ASC);
//                    } else {
//                        $containsOrderByInSameScope = array_filter(
//                            $qb->getDQLPart('orderBy'),
//                            fn($rule) => array_filter(
//                                $rule->getParts(),
//                                fn($part) => stripos($part, self::ALIAS.'.') !== false
//                            ),
//                        );
//
//                        if (!count($containsOrderByInSameScope)) {
//                            $qb->addOrderBy(sprintf('%s.%s', self::ALIAS, $sort), Criteria::ASC);
//                        } else {
//                            $rules = $qb->getDQLPart('orderBy');
//                            $qb->resetDQLPart('orderBy');
//                            $qb->addOrderBy(sprintf('%s.%s', self::ALIAS, $sort), Criteria::DESC);
//                            foreach ($rules as $rule) {
//                                foreach ($rule->getParts() as $part) {
//                                    if (stripos($part,self::ALIAS.'.') === false) {
//                                        $newOrderBy = explode(' ', $part);
////                                        $qb->addOrderBy($newOrderBy[0], Criteria::DESC);
//                                        $qb->addOrderBy($newOrderBy[0], $newOrderBy[1]);
//                                    }
//                                }
//                            }
//                        }
//                    }
//                }
//            }
//        }

        // todo finally we use Doctrine Specification, so that's a legacy code to get rid of
////        if (method_exists($criteria, 'page')) {
//////            $qb->getQuery()->setHydrationMode(AbstractQuery::HYDRATE_ARRAY);
//////            $qb->setFirstResult(($criteria->page() - 1) * 10);
//////            $qb->setMaxResults(10);
//////            $qb->addGroupBy('dctrn_result_inner');
////            // todo dirty hack because BaseQuery can't know about InitaitiveSorting,
////            // todo but we moved to Doctrine Specification so let's don't care about BaseQuery
////            $sortFields = [];
////            if (method_exists($criteria, 'sort') && $criteria->sort()) {
////                foreach ($criteria->sort() as $key => $value) {
////                    if (is_numeric($key)) {
////                        $sortFields[] = $value;
////                    } else {
////                        $qb->addSelect(sprintf('COUNT(%s) AS HIDDEN %s', $value, $key));
////                        $sortFields[] = $key;
////                    }
////                }
//////                $sortField = $criteria->sort();
//////                if ($criteria->sort() === 'joined') {
//////                    $qb->addSelect('COUNT(p.id) AS joined');
////////                    $qb->addSelect($qb->expr()->count('p.id'));
//////                    $sortField = 'joined';
//////                }
////            }
////            $options = [
////                'wrap-queries' => true,
////            ];
////            if (count($sortFields)) {
////                $options['defaultSortFieldName'] = $sortFields;
////                $options['defaultSortDirection'] = 'desc';
////            }
////            $a=$this->paginator->paginate($qb,  $criteria->page(), 10, $options);
////            if ($pagination) {
////                return $a;
////            }
////            return (array) $a->getItems();
////            $a=1;
////            $paginator = new Paginator($qb);
//
////            return $paginator->getIterator()->getArrayCopy();
//        }

        return $qb->getQuery()->getResult();
    }

    protected function findMultipleByCriteriaPagination(QueryCriteria $criteria,
                                                        string $orderByField = null,
                                                        string $orderByDirection = Criteria::ASC,
                                                        int $page = 1)
    {
        $pagination = $this->findMultipleByCriteria($criteria, $orderByField, $orderByDirection, $page, true);
        //todo PaginationCollection
        return [
            'items' => (array) $pagination->getItems(),
            'pages' => $pagination->getPageCount() - 1,
        ];
    }

    protected function queryBuilder(QueryCriteria $criteria): QueryBuilder
    {
        // TODO use https://github.com/Happyr/Doctrine-Specification
//        $qb = $this->repository->createQueryBuilder(self::ALIAS);
            $qb = $this->createQueryBuilder(self::ALIAS);

        // FIXME fast hack until Criteria::sort method will be interfaced (currently it's only for InitiativeCriteria, WIP)
        if (method_exists($criteria, 'relations') && count($criteria->relations())) {
            $qb->groupBy(self::ALIAS);
            foreach ($criteria->relations() as $alias => $relation) {
                $relation = $this->prefixField($relation);
                $qb->addSelect($alias);

                if (method_exists($criteria, 'relationsConditions') && key_exists($alias, $criteria->relationsConditions())) {
                    $qb->leftJoin($relation, $alias, Join::WITH, $criteria->relationsConditions()[$alias]);
                } else {
                    $qb->leftJoin($relation, $alias);
                }

                $qb->addGroupBy($alias);
            }
        }

        foreach ($criteria->toArray() as $field => $value) {
            $relation = explode('.', $field, 2);
            $prefixedField = $field;

            if (count($relation) > 1) {
                // fixme error could arise because of the non-unique alias
                $alias = $relation[0];
                $relation = $this->prefixField($relation[0]);
                $qb->addSelect($alias);
                $qb->addGroupBy($alias);
                $qb->leftJoin($relation, $alias);
//                $qb->join($relation, $alias);
            } else {
                $prefixedField = $this->prefixField($field);
            }

            $parameter = str_ireplace('.', '_', $field);
            $parameter = sprintf(':%s_value', $parameter);

            $expr = $qb->expr();

            if (!is_array($value)) {
                $expr = match ($criteria->operatorFor($field)) {
                    Comparison::GTE => $expr->gte($prefixedField, $parameter),
                    Comparison::GT => $expr->gt($prefixedField, $parameter),
                    Comparison::LTE => $expr->lte($prefixedField, $parameter),
                    Comparison::LT => $expr->lt($prefixedField, $parameter),
                    Comparison::IN => $expr->in($prefixedField, $parameter),
                    Comparison::NEQ => $expr->neq($prefixedField, $parameter),
                    Comparison::CONTAINS => $expr->like($prefixedField, $parameter),
                    'IS' => $expr->isNull($prefixedField),
                    default => $expr->eq($prefixedField, $parameter),
                };
    //            $expr = is_array($value)
    //                ? $expr->in($field, $parameter)
    //                : $expr->eq($field, $parameter)
    //            ;

                // todo remove if condition hack after adding OR/AND operator to Criteria interface
                if ($criteria->operatorFor($field) === Comparison::CONTAINS) {
                    $qb->orWhere($expr);
                } else {
                    $qb->andWhere($expr);
                }
            } else {
//                $expr->in($prefixedField, $parameter);
//                $qb->andWhere($expr);
                $qb->andWhere("$prefixedField IN ($parameter)");
            }

            // todo or not todo, whatever, but here we have to determine customer type by $value and pass as 3rd parameter to setParameter
            if ($criteria->operatorFor($field) !== 'IS') {
                $qb->setParameter($parameter, $value);
            }
        }

        return $qb;
    }

    private function prefixField(string $field): string
    {
        return sprintf('%s.%s', self::ALIAS, $field);
    }

    protected function createQueryBuilder($alias, $indexBy = null): QueryBuilder
    {
        // fixme research where this method used & remove it otherwise
        $em = $this->entityManager;
        $filters = $em->getFilters();

        foreach ($this->filters as $filter => $value) {
            if ($value && !$filters->isEnabled($filter)) {
                $filters->enable($filter);
                continue;
            }

            if (!$value && $filters->isEnabled($filter)) {
                $filters->disable($filter);
            }
        }

        // fixme use HINT_REFRESH
//        $em->clear();

        return $em
            ->createQueryBuilder()
            ->select($alias)
            ->from($this->getClass(), $alias, $indexBy);
    }

    protected function findMultipleByCriteriaV2($criteria): array
    {
        return $this->match($criteria);
    }

    // todo moving to new Criteria system in progress
    // todo extract to separate class and rename after accomplishment
    protected function findOneByCriteriaV2($criteria)
    {
        $collection = $this->match($criteria);

        return count($collection) ? $collection[0] : null;
    }
}
