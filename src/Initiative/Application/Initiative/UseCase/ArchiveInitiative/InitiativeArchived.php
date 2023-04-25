<?php

declare(strict_types=1);

namespace App\Initiative\Application\Initiative\UseCase\ArchiveInitiative;

use App\Identity\Domain\Company\Company;
use App\Initiative\Domain\Event\InitiativeEvent;
use App\Initiative\Domain\Initiative\InitiativeId;
use App\Shared\Application\AsyncMessage;
use App\Shared\Domain\ActiveLanguage;

final class InitiativeArchived implements AsyncMessage, InitiativeEvent
{
    private InitiativeId $initiative;
    private ActiveLanguage $language;
    private Company $company;
    private string $title;

    public function __construct(Company $company, InitiativeId $initiative, string $title, ActiveLanguage $language)
    {
        $this->initiative = $initiative;
        $this->language = $language;
        $this->company = $company;
        $this->title = $title;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function language(): ActiveLanguage
    {
        return $this->language;
    }

    public function company(): Company
    {
        return $this->company;
    }

    public function initiativeId(): InitiativeId
    {
        return $this->initiative;
    }

    public function __serialize(): array
    {
        return [];
    }

    public static function alias(): string
    {
        return 'initiative_archived';
    }
}