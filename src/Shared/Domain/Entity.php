<?php

namespace App\Shared\Domain;

interface Entity
{
    public function id(): BaseId;
}
