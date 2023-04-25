<?php

declare(strict_types=1);

namespace App\Initiative\Infrastructure\Questionnaire;

use App\Initiative\Application\Initiative\Query\AllInitiativesListQuery;
use App\Initiative\Domain\Initiative\Initiative;
use App\Initiative\Domain\Questionnaire\Answer;
use App\Initiative\Domain\Questionnaire\Questionnaire;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

final class Fixtures extends Fixture implements FixtureGroupInterface
{
    private AllInitiativesListQuery $allInitiativesListQuery;

    public function __construct(AllInitiativesListQuery $allInitiativesListQuery)
    {
        $this->allInitiativesListQuery = $allInitiativesListQuery;
    }

    public static function getGroups(): array
    {
        return ['questionnaire'];
    }

    public function load(ObjectManager $manager)
    {
        $initiatives = $this->allInitiativesListQuery->query();
//        $options = new ArrayCollection([
//            new Option(),
//        ]);
        /** @var Initiative $initiative */
        $initiative = $initiatives->toArray()[0];

        $questions = [
            $questionFreetext = new Questionnaire($initiative, Questionnaire::TYPE_FREETEXT, 'tell whaddaya think about'),
            $questionSingleSelect = new Questionnaire($initiative, Questionnaire::TYPE_SINGLE_SELECT, 'what do you think?', [
                'like it',
                'dislike it',
                'aint care at all'
            ]),
            $questionMultiSelect = new Questionnaire($initiative, Questionnaire::TYPE_MULTI_SELECT, 'meal choice?', [
                'pork',
                'hamburger',
                'eggs'
            ]),
        ];

        foreach ($questions as $question) {
            $manager->persist($question);
        }

        $manager->flush();

//        $answers = [
//            new Answer($questionFreetext, $initiative->author(), null, 'my answer'),
//            new Answer($questionSingleSelect, $initiative->author(), $questionSingleSelect->options()[1]),
//            new Answer($questionMultiSelect, $initiative->author(), $questionSingleSelect->options()[1]),
//            new Answer($questionMultiSelect, $initiative->author(), $questionMultiSelect->options()[1]),
//            new Answer($questionMultiSelect, $initiative->author(), $questionMultiSelect->options()[2]),
//        ];
//
//        foreach ($answers as $answer) {
//            $manager->persist($answer);
//        }

//        $questionFreetext->answer(new Answer($questionFreetext, $initiative->author(), null, 'my answer'));
        $questionFreetext->answerByFreetext($initiative->author(), 'my answer');
        $questionSingleSelect->answerBySelection($initiative->author(), $questionSingleSelect->options()[1]);
        $questionMultiSelect->answerBySelection(
            $initiative->author(),
            $questionMultiSelect->options()[1],
            $questionMultiSelect->options()[2]
        );

        $manager->flush();
    }
}