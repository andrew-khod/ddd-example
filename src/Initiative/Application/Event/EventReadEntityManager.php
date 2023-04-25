<?php

namespace App\Initiative\Application\Event;

use App\Initiative\Domain\Event\EventReadStatus;

interface EventReadEntityManager
{
    public function create(EventReadStatus $status): void;
    public function persist(): void;
}