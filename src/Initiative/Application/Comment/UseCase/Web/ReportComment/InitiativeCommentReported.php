<?php

namespace App\Initiative\Application\Comment\UseCase\Web\ReportComment;

use App\Identity\Domain\Company\Company;
use App\Identity\Domain\Customer\Customer;
use App\Initiative\Domain\Comment\Comment;
use App\Shared\Application\AsyncMessage;

class InitiativeCommentReported implements AsyncMessage
{
    private Comment $comment;
    private string $reason;
    private string $message;
    private Customer $reporter;
    private Company $company;
    private string $url;

    public function __construct(Comment               $comment,
                                string                $reason,
                                string                $message,
                                Customer $reporter,
                                Company $company,
                                string $url)
    {
        $this->comment = $comment;
        $this->reason = $reason;
        $this->message = $message;
        $this->reporter = $reporter;
        $this->company = $company;
        $this->url = $url;
    }

    public function comment(): Comment
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

    public function reporter(): Customer
    {
        return $this->reporter;
    }

    public function company(): Company
    {
        return $this->company;
    }

    public function url(): string
    {
        return $this->url;
    }
}