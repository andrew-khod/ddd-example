<?php

namespace App\Initiative\Domain\Event;

use App\Initiative\Domain\Initiative\InitiativeId;

interface InitiativeEvent
{
    public function initiativeId(): InitiativeId;
    public static function alias(): string;
    public function __serialize(): array;
}