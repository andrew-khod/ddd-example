<?php

declare(strict_types=1);

namespace App\Initiative\Domain\Questionnaire;

use App\Identity\Domain\Customer\Customer;
use App\Initiative\Domain\Initiative\Initiative;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Uid\Uuid;

class Questionnaire
{
    private Uuid $id;
    private ?Initiative $initiative = null;
    private string $type;
    private string $question;
    private Collection $options;
    private Collection $answers;

    private const TYPES = [
        self::TYPE_SINGLE_SELECT,
        self::TYPE_MULTI_SELECT,
        self::TYPE_FREETEXT,
    ];

    public const TYPE_SINGLE_SELECT = 'single';
    public const TYPE_MULTI_SELECT = 'multi';
    public const TYPE_FREETEXT = 'freetext';

//    TODO split Questionnaire to FreetextQuestionnaire and SelectionQuestionnaire
//    public function __construct(Initiative $initiative, string $type, string $question, Collection $options = null)
    public function __construct(Initiative $initiative, string $type, string $question, array $options = [])
    {
//        todo
//        if (!in_array($type, self::TYPES)) {
//            throw new InvalidTypeException();
//        }

        $this->id = Uuid::v4();
        $this->initiative = $initiative;
        $this->type = $type;
        $this->question = $question;

//        if ($options) {
//            $this->options = $options;
//        }

        $this->answers = new ArrayCollection();
        $this->options = new ArrayCollection();

        foreach ($options as $option) {
            $this->options->add(new Option($this, (string) $option));
        }
    }

    /**
     * @return Collection<Option>
     */
    public function options(): Collection
    {
        return $this->options;
    }

//    todo use polymorphic method answer() in splitter FreetextQuestionnaire and SelectionQuestionnaire
//    public function answer(Answer $answer): void
//    {
//        //todo check if answer is freetext and questionnaire is freetext
//        // OR answer is selector and questionnaire has these options
//        $this->answers->add($answer);
//    }

    //todo remove after splitting FreetextQuestionnare->answer()
    public function answerByFreetext(Customer $answerer, string $string): void
    {
//        $this->answers->filter(fn(Answer $answer) => $answer->isAnsweredBy($answerer))->clear();
        $answers = $this->answers->filter(fn(Answer $answer) => $answer->isAnsweredBy($answerer));
        $answers->map(fn(Answer $answer) => $this->answers->removeElement($answer));

//        todo check if answer is freetext and questionnaire is freetext
//         OR answer is selector and questionnaire has these options
        if ($this->type === self::TYPE_FREETEXT && strlen($string)) {
            $this->answers->add(new Answer($this, $answerer, null, $string));
        }
    }

    //todo remove after splitting SelectionQuestionnare->answer()
    public function answerBySelection(Customer $answerer, Option ...$options): void
    {
//        $this->answers = $this->answers->filter(fn(Answer $answer) => !$answer->isAnsweredBy($answerer));
        $answers = $this->answers->filter(fn(Answer $answer) => $answer->isAnsweredBy($answerer));
        $answers->map(fn(Answer $answer) => $this->answers->removeElement($answer));

//        todo check if answer is freetext and questionnaire is freetext
//         OR answer is selector and questionnaire has these options
        if ($this->type === self::TYPE_SINGLE_SELECT) {
            $this->answers->add(new Answer($this, $answerer, $options[0]));
        }

        if ($this->type === self::TYPE_MULTI_SELECT) {
            foreach ($options as $option) {
                $this->answers->add(new Answer($this, $answerer, $option));
            }
        }
    }

    public function equalsByContent(self $questionnaire): bool
    {
//        $this->type === $questionnaire->type() && $this->
        return false;
    }

    public function question(): string
    {
        return $this->question;
    }

    public function type(): string
    {
        return $this->type;
    }

    public function changeQuestion(string $question): void
    {
        $this->question = $question;
    }

    public function changeType(string $type): void
    {
        $this->type = $type;
    }

    public function remove(): void
    {
        $this->initiative = null;
    }

    public function id(): Uuid
    {
        return $this->id;
    }

    public function addOptions(Option ...$options): void
    {
        foreach ($options as $option) {
            $this->options->add($option);
        }
    }

    public function removeOptionsByIDs(Uuid ...$ids): void
    {
        $ids = array_map(fn(Uuid $id) => $id->toRfc4122(), $ids);

        $options = $this->options->filter(
            fn(Option $option) => in_array($option->id()->toRfc4122(), $ids)
        );

        foreach ($options as $option) {
            $this->options->removeElement($option);
        }
    }

    public function updateOptionByID(Uuid $option, string $value): void
    {
        /** @var Option $option */
        $option = $this->options->filter(
            fn(Option $o) => $o->id()->equals($option)
        )->first();

        if ($option) {
            $option->changeTitle($value);
        }
//        $option?->changeTitle($value);
    }

    public function isFreetext(): bool
    {
        return $this->type === self::TYPE_FREETEXT;
    }

    public function isSingleSelect(): bool
    {
        return $this->type === self::TYPE_SINGLE_SELECT;
    }

    public function isMultiSelect(): bool
    {
        return $this->type === self::TYPE_MULTI_SELECT;
    }

    // todo mixed is bad practice, separate SelectionQuestionnaire and TextQuestionnaire and use polymorphism
    public function answersOfCustomer(Customer $customer): Collection
    {
        return $this->answers->filter(fn(Answer $answer) => $answer->isAnsweredBy($customer));
    }

    // todo mixed is bad practice, separate SelectionQuestionnaire and TextQuestionnaire and use polymorphism
    public function answersCountByOption(Option $option): int
    {
        // fixme remove after interface segregation
        if ($this->isFreetext()) {
            return -1;
        }

        return $this->answers->filter(
            fn(Answer $answer) => $answer->option()->id()->equals($option->id())
        )->count();
    }
}