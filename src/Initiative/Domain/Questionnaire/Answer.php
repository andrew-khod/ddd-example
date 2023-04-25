<?php

declare(strict_types=1);

namespace App\Initiative\Domain\Questionnaire;

use App\Identity\Domain\Customer\AbstractCustomer;
use App\Identity\Domain\Customer\Customer;
use Symfony\Component\Uid\Uuid;

class Answer
{
    private Uuid $id;
    private Questionnaire $questionnaire;
    private AbstractCustomer $answerer;
    private ?Option $option;
    private ?string $freetext;

//    TODO split Answer to FreetextAnswer and SelectionAnswer
    public function __construct(Questionnaire $questionnaire, Customer $answerer, ?Option $option = null, ?string $freetext = null)
    {
//        todo
//        if (
//            (!$option && !$freetext) ||
//            ($questionnaire->isFreetext() && !$freetext) ||
//            (!$questionnaire->isFreetext() && (!$option || !$questionnaire->options()->contains($option)))
//        ) {
//            throw new InvalidAnswerException();
//        }

        $this->id = Uuid::v4();
        $this->questionnaire = $questionnaire;
        $this->answerer = $answerer;
        $this->option = $option;
        $this->freetext = $freetext;
    }

    public function isAnsweredBy(Customer $answerer): bool
    {
        return $this->answerer->id()->equals($answerer->id());
    }

    public function text(): ?string
    {
        return $this->freetext;
    }

    public function option(): ?Option
    {
        return $this->option;
    }
}