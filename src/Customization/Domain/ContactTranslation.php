<?php

declare(strict_types=1);

namespace App\Customization\Domain;

use App\Shared\Domain\Language;
use Symfony\Component\Uid\Uuid;

class ContactTranslation
{
    private Uuid $id;
    private string $type;
    private string $value;
    private Contact $contact;
    private Language $language;

    public function __construct(string $type,
                                string $value,
                                Contact $contact,
                                Language $language)
    {
        $this->id = Uuid::v4();
        $this->type = $type;
        $this->value = $value;
        $this->contact = $contact;
        $this->language = $language;
    }

    public function language(): string
    {
        return $this->language->name();
    }

    public function languageAsObject(): Language
    {
        return $this->language;
    }

    public function type(): string
    {
        return $this->type;
    }

    public function value(): string
    {
        return $this->value;
    }
}
