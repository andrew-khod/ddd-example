<?php

namespace App\Customization\Application;

use App\Customization\Domain\Contact;

interface ContactEntityManager
{
    public function deleteAll(): void;

    public function create(Contact $contact): void;

    public function flush(): void;
}