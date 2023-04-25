<?php

declare(strict_types=1);

namespace App\Initiative\Application\Questionnaire\UseCase\AnswerQuestionnaires;

use App\Identity\Application\User\AuthenticatedCustomer;
use App\Initiative\Application\Initiative\InitiativeEntityManager;
use App\Initiative\Application\Initiative\InitiativeException;
use App\Initiative\Application\Initiative\Query\InitiativeByCriteriaQuery;
use App\Initiative\Application\Initiative\Query\InitiativeByIdCriteria;
use App\Initiative\Domain\Initiative\Initiative;
use App\Initiative\Domain\Initiative\InitiativeId;
use App\Initiative\Domain\Questionnaire\Option;
use App\Initiative\Domain\Questionnaire\Questionnaire;
use InvalidArgumentException;
use Symfony\Component\Uid\Uuid;

final class AnswerQuestionnairesHandler
{
    private InitiativeByCriteriaQuery $initiativeByCriteriaQuery;
    private InitiativeEntityManager $initiativeEntityManager;
    private AuthenticatedCustomer $authenticatedCustomer;

    public function __construct(InitiativeByCriteriaQuery $initiativeByCriteriaQuery,
                                AuthenticatedCustomer $authenticatedCustomer,
                                InitiativeEntityManager $initiativeEntityManager)
    {
        $this->initiativeByCriteriaQuery = $initiativeByCriteriaQuery;
        $this->initiativeEntityManager = $initiativeEntityManager;
        $this->authenticatedCustomer = $authenticatedCustomer;
    }

    public function handle(AnswerQuestionnairesCommand $command): Initiative
    {
        $customer = $this->authenticatedCustomer->user();
        $criteria = new InitiativeByIdCriteria(new InitiativeId($command->initiative()));
        $initiative = $this->initiativeByCriteriaQuery->queryOne($criteria);
        $answers = $command->answers();
        $questionnaires = array_keys($answers);

        if (!$initiative) {
            throw InitiativeException::initiativeNotExist();
        }

        $initiative->questionnaires()->map(function (Questionnaire $questionnaire) use ($questionnaires, $answers, $customer) {
            $id = $questionnaire->id()->toRfc4122();

            if (!in_array($id, $questionnaires)) {
                return;
            }

            if (is_array($answers[$id])) {
                $questionnaire->answerBySelection($customer, ...$questionnaire->options()->filter(
                    fn(Option $option) => in_array($option->id()->toRfc4122(), $answers[$id])
                )->toArray());
            } elseif ($this->isUuid($answers[$id])) {
                $questionnaire->answerBySelection($customer, $questionnaire->options()->filter(
                    fn(Option $option) => $option->id()->toRfc4122() === $answers[$id]
                )->first());
            } elseif (is_string($answers[$id])) {
                $questionnaire->answerByFreetext($customer, $answers[$id]);
            }
        });

        $this->initiativeEntityManager->update();

        return $initiative;
    }

    private function isUuid(string $uuid): bool
    {
        try {
            new Uuid($uuid);
            return true;
        } catch (InvalidArgumentException $exception) {
            return false;
        }
    }
}
