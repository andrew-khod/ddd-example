<?php

declare(strict_types=1);

namespace App\Customization\Domain;

use App\Shared\Domain\Language;
use Symfony\Component\Uid\Uuid;

class Rule
{
    private Uuid $id;
    private string $title;
    private string $description;
    private Language $language;

    public function __construct(string $title, string $description, Language $language)
    {
        $this->id = Uuid::v4();
        $this->title = $title;
        $this->description = $description;
        $this->language = $language;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function description(): string
    {
        return $this->description;
    }

    public function language(): Language
    {
        return $this->language;
    }
}
