<?php

declare(strict_types=1);

namespace App\Initiative\Application\Initiative\UseCase\CommentInitiative;

final class CommentInitiativeCommand
{
    private string $initiative;
    private string $comment;

    public function __construct(string $initiative, string $comment)
    {
        $this->initiative = $initiative;
        $this->comment = $comment;
    }

    public function initiative(): string
    {
        return $this->initiative;
    }

    public function comment(): string
    {
        return $this->comment;
    }
}
