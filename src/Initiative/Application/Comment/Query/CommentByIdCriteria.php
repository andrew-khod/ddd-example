<?php

namespace App\Initiative\Application\Comment\Query;

use App\Initiative\Domain\Comment\CommentId;
use Happyr\DoctrineSpecification\Spec;
use Happyr\DoctrineSpecification\Specification\BaseSpecification;

class CommentByIdCriteria extends BaseSpecification implements CommentCriteria
{
    private CommentId $commentId;

    public function __construct(CommentId $commentId, ?string $context = null)
    {
        parent::__construct($context);

        $this->commentId = $commentId;
    }

    protected function getSpec()
    {
        return Spec::eq('id', $this->commentId->toBinary());
    }
}
