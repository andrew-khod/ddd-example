<?php

namespace App\Initiative\Application\Initiative;

use App\Initiative\Domain\Initiative\Initiative;
use App\Initiative\Domain\Initiative\InitiativeId;

interface InitiativeEntityManager
{
    public function create(Initiative $initiative): void;

    public function nextId(): InitiativeId;

    public function update(): void;
}
