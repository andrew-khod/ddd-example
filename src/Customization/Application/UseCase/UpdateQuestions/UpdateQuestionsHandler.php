<?php

declare(strict_types=1);

namespace App\Customization\Application\UseCase\UpdateQuestions;

use App\Customization\Application\QuestionEntityManager;
use App\Customization\Domain\Question;
use App\Customization\Domain\QuestionTranslation;
use App\Shared\Application\AssignedToCompanyLanguagesQuery;
use App\Shared\Application\LanguageNotExistException;
use App\Shared\Domain\Language;

final class UpdateQuestionsHandler
{
    private QuestionEntityManager $questionEntityManager;
    private AssignedToCompanyLanguagesQuery $allLanguageQuery;

    public function __construct(QuestionEntityManager $questionEntityManager, AssignedToCompanyLanguagesQuery $allLanguageQuery)
    {
        $this->questionEntityManager = $questionEntityManager;
        $this->allLanguageQuery = $allLanguageQuery;
    }

    public function handle(UpdateQuestionsCommand $command): void
    {
        //todo think about softdeleteable implementation
        //todo or calculate difference between requested questions and persisted
        // fixme use transactions/locks
        $this->questionEntityManager->deleteAll();
        $languages = $this->allLanguageQuery->query();

        // todo think about Question as Aggregate Root for QuestionTranslation, so make it
        // responsible for it's creation (create Translation inside Question)
        foreach ($command->questions() as $order => $qa) {
            $question = new Question($order);

            foreach ($qa as $language => $translation) {
                // todo move lang logic to repo
                $language = array_values(array_filter(
                    $languages,
                    fn(Language $l) => $l->name() === $language
                ));
                $language = $language
                    ? $language[0]
                    : throw new LanguageNotExistException();
                $question->translate(new QuestionTranslation(
                    $translation['question'],
                    $translation['answer'],
                    $question,
                    $language
                ));
            }

            $this->questionEntityManager->create($question);
        }

        $this->questionEntityManager->flush();
    }
}
