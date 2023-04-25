<?php

declare(strict_types=1);

namespace App\Initiative\Application\Initiative\UseCase\CreateInitiative;

use App\Identity\Application\User\AuthenticatedCustomer;
use App\Initiative\Application\Category\Query\CategoryByCriteriaQuery;
use App\Initiative\Application\Category\Query\CategoryByIdCriteria;
use App\Initiative\Application\Initiative\InitiativeEntityManager;
use App\Initiative\Application\Initiative\InitiativeException;
use App\Initiative\Domain\Initiative\Briefing;
use App\Initiative\Domain\Initiative\Duration;
use App\Initiative\Domain\Initiative\Initiative;
use App\Initiative\Domain\Initiative\Location;
use App\Initiative\Domain\Questionnaire\Questionnaire;
use App\Shared\Application\ImageManager;
use App\Shared\Domain\PreUploadedImage\PreUploadedImageCollection;

final class CreateInitiativeHandler
{
    private InitiativeEntityManager $initiativeEntityManager;

    private CategoryByCriteriaQuery $categoryByCriteriaQuery;

    private ImageManager $imageManager;

    private AuthenticatedCustomer $authenticatedCustomer;

    public function __construct(InitiativeEntityManager $initiativeEntityManager,
                                ImageManager $initiativeImageManager,
                                AuthenticatedCustomer $authenticatedCustomer,
                                CategoryByCriteriaQuery $categoryByCriteriaQuery)
    {
        $this->initiativeEntityManager = $initiativeEntityManager;
        $this->categoryByCriteriaQuery = $categoryByCriteriaQuery;
        $this->imageManager = $initiativeImageManager;
        $this->authenticatedCustomer = $authenticatedCustomer;
    }

    public function handle(CreateInitiativeCommand $command): Initiative
    {
        $id = $this->initiativeEntityManager->nextId();
        $images = new PreUploadedImageCollection();
        $briefing = new Briefing(
            $command->type(),
            $command->title(),
            $command->description(),
            $command->minimalJoinedPeople()
        );
        $duration = new Duration(
            $command->dateStart(),
            $command->dateEnd(),
        );
        $location = null;
        $criteria = new CategoryByIdCriteria($command->category());
        $categories = $this->categoryByCriteriaQuery->queryMultiple($criteria);

        if (!$categories->count()) {
            throw InitiativeException::emptyCategories();
        }

        if ($command->location()) {
            $location = new Location(
                    $command->location(),
                    $command->locationName(),
                    $command->locationRadiusValue(),
                    $command->locationRadiusUnit()
            );
        }

        // TODO save files physically AFTER saving an initiative
        if ($command->images()) {
            $images = $this->imageManager->handle($command->images());
        }

        $initiative = new Initiative(
            $id,
            $this->authenticatedCustomer->user(),
            $categories,
            $images,
            $briefing,
            $duration,
            $location
        );

        //todo Initiative should create Questionnaire since Initiative is an Aggregate Root.
        // pass QuestionnaireValidatedStructure to Initiative to create a Questionnaire entity
        $questionnaires = [];

        foreach ($command->questionnaires() as $questionnaire) {
//            $questionnaires[] = new Questionnaire($initiative, Questionnaire::TYPE_FREETEXT, 'tell whaddaya think about');
            //todo exncapsulate array into dto or another object
            $questionnaires[] = new Questionnaire($initiative, $questionnaire['type'], $questionnaire['question'], key_exists('options', $questionnaire) ? $questionnaire['options'] : []);
        }

        $initiative->assignQuestionnaires(...$questionnaires);

        $this->initiativeEntityManager->create($initiative);

        // TODO if Initiative not created then remove uploaded files

        return $initiative;
    }
}
