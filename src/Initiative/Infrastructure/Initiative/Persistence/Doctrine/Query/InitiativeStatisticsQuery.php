<?php

declare(strict_types=1);

namespace App\Initiative\Infrastructure\Initiative\Persistence\Doctrine\Query;

use App\Identity\Infrastructure\Company\SwitchableActiveTenant;
use App\Initiative\Application\Initiative\Query\InitiativeStatisticsQuery as InitiativeStatisticsQueryAlias;
use App\Initiative\Domain\Comment\Comment;
use App\Initiative\Domain\Initiative\Initiative;
use App\Initiative\Domain\Initiative\InitiativeStatistics;
use DateTime;

class InitiativeStatisticsQuery implements InitiativeStatisticsQueryAlias
{
    private SwitchableActiveTenant $tenant;

    public function __construct(SwitchableActiveTenant $tenant)
    {
        $this->tenant = $tenant;
    }

    public function query(): InitiativeStatistics
    {
        // todo implement alternatively in Criteria/Specification way or reuse existing old InitiativeQuery
        $weekAgo = new DateTime('-7 days');
//        $statistics = $this->tenant->entityManager()->createQuery('SELECT COUNT(i.id) FROM App\Initiative\Domain\Initiative\Initiative i UNION SELECT COUNT(c.id) FROM App\Initiative\Domain\Comment')
//        $statistics = $this->tenant->entityManager()->getConnection()->prepare('SELECT count(i.id) as total FROM initiative i');
        $initiatives = $this->tenant->entityManager()->createQueryBuilder()
            ->addSelect('count(i) as total_initiatives')
//            ->addSelect('count(c) as total_comments')
            ->from(Initiative::class, 'i')
//            ->join(Comment::class, 'c')
            ->getQuery()
            ->getSingleResult()['total_initiatives'];
        $lastWeekInitiatives = $this->tenant->entityManager()->createQueryBuilder()
            ->addSelect('count(i) as total_initiatives')
//            ->addSelect('count(c) as total_comments')
            ->from(Initiative::class, 'i')
//            ->where('i.created BETWEEN DATE_SUB(CURRENT_TIMESTAMP(), INTERVAL 1 WEEK) AND CURRENT_TIMESTAMP()')
            ->where('i.created >= :weekAgo')
            ->setParameter('weekAgo', $weekAgo)
//            ->join(Comment::class, 'c')
            ->getQuery()
            ->getSingleResult()['total_initiatives'];
        $comments = $this->tenant->entityManager()->createQueryBuilder()
            ->addSelect('count(c) as total_comments')
            ->from(Comment::class, 'c')
            ->getQuery()
            ->getSingleResult()['total_comments'];
        $lastWeekComments = $this->tenant->entityManager()->createQueryBuilder()
            ->addSelect('count(c) as total_comments')
            ->from(Comment::class, 'c')
            ->where('c.created >= :weekAgo')
            ->setParameter('weekAgo', $weekAgo)
            ->getQuery()
            ->getSingleResult()['total_comments'];
//        ->fetch();
        return new InitiativeStatistics(
            $initiatives,
            $lastWeekInitiatives,
            0,
            $comments,
            $lastWeekComments,
        );
    }
}
