<?php

declare(strict_types=1);

namespace App\Initiative\Domain\Comment;

use App\Identity\Domain\Customer\AbstractCustomer;
use App\Initiative\Domain\Initiative\Initiative;
use App\Shared\Domain\Entity;
use DateTime;

class Comment implements Entity
{
    private CommentId $id;

    private string $comment;

    private DateTime $created;

    private DateTime $updated;

    private Initiative $initiative;

    private AbstractCustomer $customer;

    private DateTime $archived_at;

    public function __construct(CommentId $id,
                                string $comment,
                                Initiative $initiative,
                                AbstractCustomer $customer)
    {
        $this->id = $id;
        $this->comment = $comment;
        $this->initiative = $initiative;
        $this->customer = $customer;
    }

    public function id(): CommentId
    {
        return $this->id;
    }

    public function __toString(): string
    {
        return $this->comment;
    }

    public function author(): AbstractCustomer
    {
        return $this->customer;
    }

    public function posted(): DateTime
    {
        return $this->created;
    }

    public function initiative(): Initiative
    {
        return $this->initiative;
    }

    public function archive()
    {
        $this->archived_at = new DateTime();
    }
}
