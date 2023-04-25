<?php

namespace App\Initiative\Application\Initiative\Query;

use App\Shared\Application\QueryCriteria;

interface InitiativeCriteria extends QueryCriteria
{
    // todo move to QueryCriteria when needed for other Criterias
    // todo return new Sort(string $sort) to
    // todo disallow incorrect sorting fields and to simplify return type (mixed might be not so cool)
    // todo by hiding complex logic under the class
    public function sort(): ?string;
//    public function sort(): array|string;

//    public function page(): int;
}
