<?php

namespace App\Initiative\Application\Event;

use App\Initiative\Domain\Event\Event;

interface EventEntityManager
{
    public function create(Event $event): void;
    public function persist(): void;
}