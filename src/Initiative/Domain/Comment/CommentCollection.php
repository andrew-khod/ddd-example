<?php

declare(strict_types=1);

namespace App\Initiative\Domain\Comment;

use App\Shared\Domain\BaseCollection;

class CommentCollection extends BaseCollection
{
    public function __construct(Comment ...$comments)
    {
        parent::__construct(...$comments);
    }
}
