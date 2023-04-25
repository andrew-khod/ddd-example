<?php

namespace App\Initiative\Application\Initiative\Query;

use App\Initiative\Domain\Initiative\InitiativeCollection;

interface AllInitiativesListQuery
{
    public function query(): InitiativeCollection;
}
