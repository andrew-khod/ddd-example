<?php

declare(strict_types=1);

namespace App\Initiative\Application\Initiative\UseCase\Dashboard\UpdateInitiative;

use App\Initiative\Application\Category\Query\CategoryByCriteriaQuery;
use App\Initiative\Application\Category\Query\CategoryByIdCriteria;
use App\Initiative\Application\Image\Query\ImageByCriteriaQuery;
use App\Initiative\Application\Image\Query\ImageByIdCriteria;
use App\Initiative\Application\Initiative\InitiativeEntityManager;
use App\Initiative\Application\Initiative\InitiativeException;
use App\Initiative\Application\Initiative\Query\InitiativeByCriteriaQuery;
use App\Initiative\Application\Initiative\Query\InitiativeByIdCriteria;
use App\Initiative\Application\Initiative\UseCase\UpdateInitiative\UpdateInitiativeCommand;
use App\Initiative\Domain\Category\CategoryId;
use App\Initiative\Domain\Image\Image;
use App\Initiative\Domain\Image\ImageId;
use App\Initiative\Domain\Initiative\Duration;
use App\Initiative\Domain\Initiative\Initiative;
use App\Initiative\Domain\Initiative\InitiativeId;
use App\Initiative\Domain\Initiative\Location;
use App\Shared\Application\ImageManager;
use App\Shared\Application\MessageBus;
use App\Shared\Domain\ActiveLanguage;
use DateTime;
use DateTimeZone;

final class UpdateInitiativeHandler
{
    private InitiativeEntityManager $initiativeEntityManager;
    private CategoryByCriteriaQuery $categoryByCriteriaQuery;
    private ImageManager $imageManager;
    private InitiativeByCriteriaQuery $initiativeByCriteriaQuery;
    private ImageByCriteriaQuery $imageByCriteriaQuery;
    private MessageBus $messageBus;
    private ActiveLanguage $activeLanguage;
    private array $changes = [];

    public function __construct(InitiativeEntityManager   $initiativeEntityManager,
                                InitiativeByCriteriaQuery $initiativeByCriteriaQuery,
                                ImageManager              $initiativeImageManager,
                                CategoryByCriteriaQuery   $categoryByCriteriaQuery,
                                MessageBus                $messageBus,
                                ImageByCriteriaQuery      $imageByCriteriaQuery,
                                ActiveLanguage            $activeLanguage)
    {
        $this->initiativeEntityManager = $initiativeEntityManager;
        $this->categoryByCriteriaQuery = $categoryByCriteriaQuery;
        $this->imageManager = $initiativeImageManager;
        $this->initiativeByCriteriaQuery = $initiativeByCriteriaQuery;
        $this->imageByCriteriaQuery = $imageByCriteriaQuery;
        $this->messageBus = $messageBus;
        $this->activeLanguage = $activeLanguage;
    }

    // todo think about extending common logic with WebAppUpdateInitiativeHandler
    public function handle(UpdateInitiativeCommand $command): Initiative
    {
        $initiativeId = new InitiativeId($command->initiative());
        $criteria = new InitiativeByIdCriteria($initiativeId);
        $initiative = $this->initiativeByCriteriaQuery->queryOne($criteria);

        if (!$initiative) {
            throw InitiativeException::initiativeNotExist();
        }

        // TODO use the Workflow to reject an update of initiative with wrong status
        $this->update($initiative, $command);
        $this->initiativeEntityManager->update();

        if (count($this->changes)) {
//            $this->messageBus->dispatch(new InitiativeUpdated($this->tenant->company(), $initiativeId, $this->changes, $this->activeLanguage));
        }

        return $initiative;
    }

    private function update(Initiative $initiative, UpdateInitiativeCommand $command): void
    {
        $this->briefingUpdate($initiative, $command);
        $this->durationUpdate($initiative, $command);
        $this->locationUpdate($initiative, $command);
        $this->categoriesUpdate($initiative, $command);
        $this->imagesUpdate($initiative, $command);
    }

    private function briefingUpdate(Initiative $initiative, UpdateInitiativeCommand $command): void
    {
        $briefing = $initiative->briefing();

        if ($command->isTypeUpdateRequested()) {
            $briefing = $briefing->migrateTo($command->type());
        }

        if ($command->isTitleUpdateRequested() && $briefing->title() !== $command->title()) {
            $old = $briefing->title();
            $briefing = $briefing->rename($command->title());
            $this->trackChanges('title', $old, $command->title());
        }

        if ($command->isDescriptionUpdateRequested() && $briefing->description() !== $command->description()) {
            $old = $briefing->description();
            $briefing = $briefing->updateContent($command->description());
            $this->trackChanges('description', $old, $command->description());
        }

        if ($command->isMinimalJoinedPeopleUpdateRequested()) {
            $briefing = $briefing->requirePeople($command->minimalJoinedPeople());
        }

        $initiative->rebrief($briefing);
    }

    private function trackChanges(string $property, mixed $oldValue, mixed $newValue): void
    {
        $this->changes[] = [
            'property' => $property,
            'old' => $oldValue,
            'new' => $newValue,
        ];
    }

    private function durationUpdate(Initiative $initiative, UpdateInitiativeCommand $command): void
    {
        $duration = $initiative->duration();
        $dateStart = $duration->start();
        $dateEnd = $duration->end();
        $oldDuration = clone $duration;
        $isUpdated = false;
        $start = $end = null;

        if (!$command->isDateUpdateRequested()) {
            return;
        }

        if ($command->isStartingDateUpdateRequested()) {
            $start = DateTime::createFromFormat('Y-m-d\TH:i:sP', $command->dateStart());
            $start = new DateTime($start->format('Y-m-d H:i:s'), new DateTimeZone('UTC'));

            if ($duration->start() != $start) {
                $dateStart = $command->dateStart();
                $isUpdated = true;
            }
        }

        if ($command->isEndingDateUpdateRequested()) {
            $end = DateTime::createFromFormat('Y-m-d\TH:i:sP', $command->dateEnd());
            $end = new DateTime($end->format('Y-m-d H:i:s'), new DateTimeZone('UTC'));

            if ($duration->end() != $end) {
                $dateEnd = $command->dateEnd();
                $isUpdated = true;
            }
        }

        $duration = new Duration($dateStart, $dateEnd);

        if ($isUpdated) {
            $initiative->reschedule($duration);
            $this->trackChanges('duration', $oldDuration, $duration);
        }
    }

    private function locationUpdate(Initiative $initiative, UpdateInitiativeCommand $command): void
    {
        $oldLocation = $initiative->location();
        $location = $oldLocation ? clone $oldLocation : null;;

//        if ($command->isLocationUpdateRequested() && !$command->location() && $location) {
//            $initiative->unlocate();
//        }

        // because we have nothing to track, return earlier
        if (!$command->isLocationUpdateRequested()
            && !$command->isLocationNameUpdateRequested()
            && !$command->isLocationRadiusUpdateRequested()
            && !$command->isLocationRadiusUnitUpdateRequested()
        ) {
            return;
        }

        if ($command->location()
            && $command->locationName()
//            && $command->locationRadiusValue()
//            && $command->locationRadiusUnit()
            && !$location
        ) {
            $location = new Location(
                $command->location(),
                $command->locationName(),
                $command->locationRadiusValue(),
                $command->locationRadiusUnit()
            );
            $initiative->relocate($location);
            $this->trackChanges('location', $oldLocation, $location);
            //            throw InitiativeException::noLocationChosenYet();
            return;
        }

        if ($location) {
            $dirty = false;

            if ($command->isLocationUpdateRequested() && !$command->location()) {
                $initiative->unlocate();
                $this->trackChanges('location', $oldLocation, null);
                return;
            }

            if ($command->isLocationNameUpdateRequested() && (string) $location !== $command->location()) {
                $location = $location->relocate($command->location(), $command->locationName());
                $dirty = true;
            }

            if ($command->isLocationRadiusUpdateRequested() && $location->radiusValue() !== $command->locationRadiusValue()) {
                $location = $location->updateRadius($command->locationRadiusValue());
                $dirty = true;
            }

            if ($command->isLocationRadiusUnitUpdateRequested() && $location->radiusUnit() !== $command->locationRadiusUnit()) {
                $location = $location->updateRadiusUnit($command->locationRadiusUnit());
                $dirty = true;
            }

            if ($dirty) {
                $initiative->relocate($location);
                $this->trackChanges('location', $oldLocation, $location);
            }
        }

//        if ($location) {
//            if ($command->isLocationUpdateRequested() && !$command->location()) {
//                $initiative->unlocate();
//                $this->trackChanges('location', $oldLocation, $command->location());
//
//                return;
//            }
//
//            if ($command->isLocationNameUpdateRequested()) {
//                $location = $location->relocate($command->location(), $command->locationName());
//            }
//
//            if ($command->isLocationRadiusUpdateRequested()) {
//                $location = $location->updateRadius($command->locationRadiusValue());
//            }
//
//            if ($command->isLocationRadiusUnitUpdateRequested()) {
//                $location = $location->updateRadiusUnit($command->locationRadiusUnit());
//            }
//        } else {
//            if (!$command->hasFullLocationUpdateRequest()) {
//                throw InitiativeException::noLocationChosenYet();
//            }
//
//            $location = new Location(
//                $command->location(),
//                $command->locationName(),
//                $command->locationRadiusValue(),
//                $command->locationRadiusUnit()
//            );
//        }
//
//        $initiative->relocate($location);
//        $this->trackChanges('location', $oldLocation, $location);
    }

    private function categoriesUpdate(Initiative $initiative, UpdateInitiativeCommand $command): void
    {
        if (!$command->isCategoryUpdateRequested()) {
            return;
        }

        $criteria = new CategoryByIdCriteria(new CategoryId($command->category()));
        $categories = $this->categoryByCriteriaQuery->queryMultiple($criteria);

        if (!$categories->count()) {
            throw InitiativeException::emptyCategories();
        }

        $initiative->moveToCategories($categories);
    }

    private function imagesUpdate(Initiative $initiative, UpdateInitiativeCommand $command): void
    {
        // TODO save files physically AFTER saving an initiative
        if ($command->isImagesToRemoveUpdateRequested()) {
            $this->imagesToRemoveUpdate($initiative, $command);
        }

        if (!$command->isImagesUpdateRequested()) {
            return;
        }

        $images = $this->imageManager->handle($command->images());

        $initiative->addImages($images);
    }

    private function imagesToRemoveUpdate(Initiative $initiative, UpdateInitiativeCommand $command)
    {
        $images = array_map(
            fn(string $id) => new ImageId($id),
            $command->imagesToRemove()
        );
        $criteria = new ImageByIdCriteria(...$images);
        $images = $this->imageByCriteriaQuery->queryMultiple($criteria);

        array_map(function (Image $image) use ($initiative) {
            if ($image->initiative()->equals($initiative)) {
                $initiative->removeImage($image);
                $this->imageManager->remove($image->pathname());
            }
        }, $images->toArray());
    }
}
