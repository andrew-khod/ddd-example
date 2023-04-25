<?php

declare(strict_types=1);

namespace App\Identity\Application\Customer\UseCase\DeleteCustomer;

use App\Identity\Domain\Company\Company;
use App\Initiative\Domain\Event\InitiativeEvent;
use App\Initiative\Domain\Initiative\InitiativeId;
use App\Shared\Application\AsyncMessage;

final class InitiativesArchived implements AsyncMessage, InitiativeEvent
//final class InitiativesArchived implements AsyncMessage
{
    private array $initiatives;
    private Company $company;
//    private ActiveLanguage $language;

    public function __construct(Company        $company,
//                                ActiveLanguage $language,
                                InitiativeId   ...$initiatives)
    {
        $this->initiatives = $initiatives;
        $this->company = $company;
//        $this->language = $language;
    }

    public function initiatives(): array
    {
        return $this->initiatives;
    }

    public function company(): Company
    {
        return $this->company;
    }

//    public function language(): ActiveLanguage
//    {
//        return $this->language;
//    }

    // fixme we have multiple ids instead, so use different interface or modify the old one?
    public function initiativeId(): InitiativeId
    {
        return $this->initiatives[0];
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