<?php

declare(strict_types=1);

namespace App\Initiative\Application\Comment\UseCase\Dashboard\RemoveComment;

final class RemoveCommentCommand
{
    private string $comment;

    public function __construct(string $comment)
    {
        $this->comment = $comment;
    }

    public function comment(): string
    {
        return $this->comment;
    }
}
