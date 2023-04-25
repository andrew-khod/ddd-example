<?php

declare(strict_types=1);

namespace App\Initiative\Application\Initiative\UseCase\UpdateInitiative;

use App\Identity\Domain\Company\Company;
use App\Initiative\Domain\Event\InitiativeEvent;
use App\Initiative\Domain\Initiative\InitiativeId;
use App\Shared\Application\AsyncMessage;
use App\Shared\Domain\ActiveLanguage;

final class InitiativeUpdated implements AsyncMessage, InitiativeEvent
{
    private array $changes;
    private ActiveLanguage $language;
    private Company $company;
    private InitiativeId $initiative;

    public function __construct(Company $company, InitiativeId $initiative, array $changes, ActiveLanguage $language)
    {
        $this->changes = $changes;
        $this->language = $language;
        $this->company = $company;
        $this->initiative = $initiative;
    }

    public function language(): ActiveLanguage
    {
        return $this->language;
    }

    public function changes(): array
    {
        return $this->changes;
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
        return [
            'changes' => $this->changes,
        ];
    }

    public static function alias(): string
    {
        return 'initiative_updated';
    }
}