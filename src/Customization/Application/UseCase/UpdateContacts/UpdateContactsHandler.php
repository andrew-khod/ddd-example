<?php

declare(strict_types=1);

namespace App\Customization\Application\UseCase\UpdateContacts;

use App\Customization\Application\ContactEntityManager;
use App\Customization\Domain\Contact;
use App\Customization\Domain\ContactTranslationInfo;
use App\Shared\Application\AssignedToCompanyLanguagesQuery;
use App\Shared\Application\LanguageNotExistException;
use App\Shared\Domain\Language;

final class UpdateContactsHandler
{
    private ContactEntityManager $contactEntityManager;
    private AssignedToCompanyLanguagesQuery $allLanguageQuery;

    public function __construct(ContactEntityManager $contactEntityManager, AssignedToCompanyLanguagesQuery $allLanguageQuery)
    {
        $this->contactEntityManager = $contactEntityManager;
        $this->allLanguageQuery = $allLanguageQuery;
    }

    public function handle(UpdateContactsCommand $command): void
    {
        //todo think about softdeleteable implementation
        //todo or calculate difference between requested contacts and persisted
        // fixme use transactions/locks
        $this->contactEntityManager->deleteAll();
        $languages = $this->allLanguageQuery->query();

        foreach ($command->contacts() as $order => $contact) {
            $translationsInfo = [];

            foreach ($contact as $language => $translations) {
                // todo move lang logic to repo
                $language = array_values(array_filter(
                    $languages,
                    fn(Language $l) => $l->name() === $language
                ));
                $language = $language
                    ? $language[0]
                    : throw new LanguageNotExistException();
                $translationsInfo[] = new ContactTranslationInfo($language, $translations);
            }

            $this->contactEntityManager->create(new Contact($order, ...$translationsInfo));
        }

        $this->contactEntityManager->flush();
    }
}
