<?php

declare(strict_types=1);

namespace App\Initiative\Infrastructure\Comment\Query;

use App\Initiative\Application\Comment\Query\CommentByCriteriaQuery as CommentByCriteriaQueryInterface;
use App\Initiative\Application\Comment\Query\CommentCriteria;
use App\Initiative\Domain\Comment\Comment;
use App\Shared\Infrastructure\Doctrine\Query\SwitchableTenantBaseQuery;

class CommentByCriteriaQuery extends SwitchableTenantBaseQuery implements CommentByCriteriaQueryInterface
{
    protected function getClass(): string
    {
        return Comment::class;
    }

    public function queryOne(CommentCriteria $criteria): ?Comment
    {
        return $this->findOneByCriteriaV2($criteria);
    }
}
