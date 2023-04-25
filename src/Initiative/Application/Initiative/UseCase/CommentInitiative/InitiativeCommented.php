<?php

declare(strict_types=1);

namespace App\Initiative\Application\Initiative\UseCase\CommentInitiative;

use App\Identity\Domain\Company\Company;
use App\Initiative\Domain\Comment\Comment;
use App\Initiative\Domain\Comment\CommentId;
use App\Initiative\Domain\Event\InitiativeEvent;
use App\Initiative\Domain\Initiative\InitiativeId;
use App\Shared\Application\AsyncMessage;
use Symfony\Contracts\EventDispatcher\Event;

final class InitiativeCommented extends Event implements AsyncMessage, InitiativeEvent
{
    private CommentId $comment;
//    private Comment $comment;
    private Company $company;
    private InitiativeId $initiative;

//    public function __construct(Company $company, InitiativeId $initiative, Comment $comment)
    public function __construct(Company $company, InitiativeId $initiative, CommentId $comment)
    {
        $this->comment = $comment;
        $this->company = $company;
        $this->initiative = $initiative;
    }

//    public function comment(): Comment
    public function comment(): CommentId
    {
        return $this->comment;
    }

    public function company(): Company
    {
        return $this->company;
    }

    public function initiativeId(): InitiativeId
    {
        return $this->initiative;
    }

    public function __serialize(): array
    {
        return [
            'comment' => $this->comment,
            'company' => $this->company,
//            'comment' => $this->comment->id(),
        ];
    }

    public static function alias(): string
    {
        return 'initiative_commented';
    }
}