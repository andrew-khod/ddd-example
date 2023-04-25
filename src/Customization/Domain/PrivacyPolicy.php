<?php

declare(strict_types=1);

namespace App\Customization\Domain;

use App\Shared\Domain\Language;
use Symfony\Component\Uid\Uuid;

class PrivacyPolicy
{
    private Uuid $id;
    private string $url;
    private Language $language;

    public function __construct(string $url, Language $language)
    {
        $this->id = Uuid::v4();
        $this->url = $url;
        $this->language = $language;
    }

    public function url(): string
    {
        return $this->url;
    }

    public function language(): Language
    {
        return $this->language;
    }
}
