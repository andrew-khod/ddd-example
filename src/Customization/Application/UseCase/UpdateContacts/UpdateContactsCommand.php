<?php

declare(strict_types=1);

namespace App\Customization\Application\UseCase\UpdateContacts;

final class UpdateContactsCommand
{
    private array $contacts;

    public function __construct(array $contacts)
    {
        $this->contacts = $contacts;
    }

    public function contacts(): array
    {
        return $this->contacts;
    }
}
