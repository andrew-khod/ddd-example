<?php

declare(strict_types=1);

namespace App\Shared\UI\Http\Request;

use App\Identity\Infrastructure\Company\SwitchableActiveTenant;
use App\Identity\Infrastructure\Customer\ActiveLanguage;
use App\Shared\Application\AssignedToCompanyLanguagesQuery;
use App\Shared\Application\AvailableLanguagesQuery;
use App\Shared\Domain\AvailableLanguage;
use App\Shared\Domain\Language;
use Symfony\Component\HttpKernel\Event\ControllerEvent;

class LocaleListener
{
    private AssignedToCompanyLanguagesQuery $assignedToCompanyLanguagesQuery;
    private AvailableLanguagesQuery $availableLanguagesQuery;
    private SwitchableActiveTenant $tenant;

    public function __construct(AssignedToCompanyLanguagesQuery $assignedToCompanyLanguagesQuery,
                                AvailableLanguagesQuery         $availableLanguagesQuery,
                                SwitchableActiveTenant          $tenant)
//    public function __construct(AssignedToCompanyLanguagesQuery $assignedToCompanyLanguagesQuery)
//    public function __construct(AvailableLanguagesQuery $availableLanguagesQuery)
    {
        $this->assignedToCompanyLanguagesQuery = $assignedToCompanyLanguagesQuery;
        $this->availableLanguagesQuery = $availableLanguagesQuery;
        $this->tenant = $tenant;
    }

//    public function onKernelRequest(RequestEvent $event)
    public function onKernelController(ControllerEvent $event)
    {
        // TODO or use the built-in symfony's _locale approach using a url-generated locale setup
        $request = $event->getRequest();
        $locale = $request->getPreferredLanguage();
        $language = $locale ? locale_parse($locale)['language'] : null;
        $languages = [];
//        $languages = array_map(
//            fn(AvailableLanguage $language) => $language->name(),
//            $this->availableLanguagesQuery->query()
//        );

        if ($this->tenant->company()) {
            $languages = array_map(
                fn(Language $language) => $language->name(),
                $this->assignedToCompanyLanguagesQuery->query()
            );
        } else {
            $languages = array_map(
                fn(AvailableLanguage $language) => $language->name(),
                $this->availableLanguagesQuery->query()
            );
        }

        $request->setLocale(ActiveLanguage::DEFAULT_LANGUAGE);

        // fixme allow languages available in database
        if (in_array($language, $languages)) {
            $request->setLocale($language);
        }

        // fixme temporary fix
        // the problem is: when we use anywhere $em->filters->enable/disable, this passed until you
        // somewhere not disable/enable it again, even when you query $entity->relation.
        // so ideally we have to disable filters after each Query, but can't figure out how to do that constantly.
//        $filters = $this->tenant->entityManager()->getFilters();
//        if ($filters->isEnabled('softdeleteable')) {
//            $filters->disable('softdeleteable');
//        }
    }
}