<?php

namespace App\Initiative\Application\Comment\Query;

use App\Initiative\Domain\Comment\Comment;

interface CommentByCriteriaQuery
{
    public function queryOne(CommentCriteria $criteria): ?Comment;

//    public function queryMultiple(CommentCriteria $criteria): CommentCollection;
}