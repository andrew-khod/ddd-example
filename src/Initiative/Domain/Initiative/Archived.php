<?php

declare(strict_types=1);

namespace App\Initiative\Domain\Initiative;

class Archived implements Status
{
    public function __toString(): string
    {
        return StatusList::ARCHIVED;
    }

    public function transaction(): string
    {
        return StatusTransaction::ARCHIVE;
    }
}