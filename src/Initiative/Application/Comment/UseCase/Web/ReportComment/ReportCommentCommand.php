<?php

declare(strict_types=1);

namespace App\Initiative\Application\Comment\UseCase\Web\ReportComment;

final class ReportCommentCommand
{
    private string $comment;
    private string $reason;
    private string $message;
    private string $url;

    public function __construct(string $comment, string $reason, string $message, string $url)
    {
        $this->comment = $comment;
        $this->reason = $reason;
        $this->message = $message;
        $this->url = $url;
    }

    public function comment(): string
    {
        return $this->comment;
    }

    public function reason(): string
    {
        return $this->reason;
    }

    public function message(): string
    {
        return $this->message;
    }

    public function url(): string
    {
        return $this->url;
    }
}
