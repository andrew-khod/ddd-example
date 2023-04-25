<?php

namespace App\Initiative\Application\Initiative\Query;

use App\Initiative\Domain\Initiative\InitiativeStatistics;

interface InitiativeStatisticsQuery
{
    public function query(): InitiativeStatistics;
}