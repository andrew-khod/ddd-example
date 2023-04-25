<?php

namespace App\Initiative\UI\Dashboard\Initiative\Controller;

use App\Initiative\Application\Category\Query\AllCategoriesListQuery;
use App\Initiative\Application\Category\UseCase\UpdateCategories\UpdateCategoriesCommand;
use App\Initiative\Application\Category\UseCase\UpdateCategories\UpdateCategoriesHandler;
use App\Initiative\Application\Comment\UseCase\Dashboard\RemoveComment\RemoveCommentCommand;
use App\Initiative\Application\Comment\UseCase\Dashboard\RemoveComment\RemoveCommentHandler;
use App\Initiative\Application\Image\UseCase\RemoveImage\RemoveImageCommand;
use App\Initiative\Application\Image\UseCase\RemoveImage\RemoveImageHandler;
use App\Initiative\Application\Initiative\Query\InitiativeByFilterCriteria;
use App\Initiative\Application\Initiative\Query\InitiativeStatisticsQuery;
use App\Initiative\Application\Initiative\UseCase\ArchiveInitiative\ArchiveInitiativeCommand;
use App\Initiative\Application\Initiative\UseCase\Dashboard\ArchiveInitiative\ArchiveInitiativeHandler;
use App\Initiative\Application\Initiative\UseCase\Dashboard\RestoreInitiative\RestoreInitiativeCommand;
use App\Initiative\Application\Initiative\UseCase\Dashboard\RestoreInitiative\RestoreInitiativeHandler;
use App\Initiative\Application\Initiative\UseCase\Dashboard\UpdateInitiative\UpdateInitiativeHandler;
use App\Initiative\Application\Initiative\UseCase\UpdateInitiative\InitiativeContentModification;
use App\Initiative\Application\Initiative\UseCase\UpdateInitiative\UpdateInitiativeCommand;
use App\Initiative\Domain\Initiative\InitiativeCollection;
use App\Initiative\Infrastructure\Initiative\Persistence\Doctrine\Query\InitiativeListQuery;
use App\Initiative\UI\Dashboard\Initiative\InitiativeCollectionPayload;
use App\Initiative\UI\Dashboard\Initiative\InitiativePayload;
use App\Initiative\UI\Dashboard\Initiative\Request\FilterInitiativeListRequest;
use App\Initiative\UI\Dashboard\Initiative\Request\UpdateCategoriesRequest;
use App\Initiative\UI\Web\Initiative\Controller\ImageManager;
use App\Initiative\UI\Web\Initiative\Request\UpdateInitiativeRequest;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use OpenApi\Annotations as OA;
use OpenApi\Annotations\Delete;
use OpenApi\Annotations\Get;
use OpenApi\Annotations\MediaType;
use OpenApi\Annotations\Post;
use OpenApi\Annotations\Property;
use OpenApi\Annotations\RequestBody;
use OpenApi\Annotations\Schema;
use OpenApi\Annotations\Tag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;

/**
 * @Tag(name="Initiatives")
 */
class InitiativeController extends AbstractFOSRestController
{
    private ImageManager $imageManager;

    public function __construct()
    {
        $this->imageManager = new ImageManager();
    }

    /**
     * @Post(summary="Initiatives list", description="Initiatives list")
     */
//    public function initiatives(InitiativeListQuery $initiativeByCriteriaQuery, AuthenticatedCustomer $authenticatedCustomer, FilterInitiativeListRequest $request): Response
    public function initiatives(InitiativeListQuery $initiativeByCriteriaQuery, FilterInitiativeListRequest $request, Security $security): Response
//    public function initiatives()
    {
//        return [];
//
//        // TODO make separate endpoints like initiatives/archived, initiatives/active, initiatives/expired
        $filter = $request->get('filter') ?? [];
//        $criteria = (new InitiativeByFilterCriteria($filter))->excludeArchived();
        $criteria = new InitiativeByFilterCriteria($filter);
        $initiatives = $initiativeByCriteriaQuery->queryMultipleNoPagination($criteria);
//        $initiatives = $initiativeByCriteriaQuery->queryMultipleByPagination($criteria);
//        $initiatives = $initiativeByCriteriaQuery->queryMultiple($criteria);

        $view = $this->view(new InitiativeCollectionPayload(new InitiativeCollection(...$initiatives)), Response::HTTP_OK);

        return $this->handleView($view);
    }

    /**
     * @Get(summary="Initiatives statistics", description="Initiatives statistics")
     */
    public function statistics(InitiativeStatisticsQuery $initiativeStatisticsQuery): Response
    {
        $view = $this->view($initiativeStatisticsQuery->query(), Response::HTTP_OK);

        return $this->handleView($view);
    }

    /**
     * @Post(summary="Archive initiative", description="Archive initiative")
     */
    public function archive(ArchiveInitiativeHandler $archiveInitiativeHandler, Request $request): Response
    {
        $command = new ArchiveInitiativeCommand($request->get('initiative'));
        $initiative = $archiveInitiativeHandler->handle($command);

        $view = $this->view(new InitiativePayload($initiative), Response::HTTP_OK);

        return $this->handleView($view);
    }

    /**
     * @Delete(summary="Restore initiative", description="Restore initiative")
     */
    public function restore(RestoreInitiativeHandler $restoreInitiativeHandler, Request $request): Response
    {
        $command = new RestoreInitiativeCommand($request->get('initiative'));
        $initiative = $restoreInitiativeHandler->handle($command);

        $view = $this->view(new InitiativePayload($initiative), Response::HTTP_OK);

        return $this->handleView($view);
    }

    /**
     * @Delete(summary="Remove comment", description="Remove comment")
     */
    public function removeComment(RemoveCommentHandler $removeCommentHandler, Request $request): Response
    {
        $command = new RemoveCommentCommand($request->get('comment'));
        $removeCommentHandler->handle($command);

        $view = $this->view(null, Response::HTTP_OK);

        return $this->handleView($view);
    }

    /**
     * @Delete(summary="Remove image", description="Remove image")
     */
    public function removeImage(RemoveImageHandler $removeImageHandler, Request $request): Response
    {
        $command = new RemoveImageCommand($request->get('image'));
        $removeImageHandler->handle($command);

        $view = $this->view(null, Response::HTTP_OK);

        return $this->handleView($view);
    }

    /**
     * @Get(summary="Categories list", description="Categories list")
     */
    public function categories(AllCategoriesListQuery $allCategoriesListQuery): Response
    {
        $categories = $allCategoriesListQuery->query();

        $view = $this->view(new CategoryCollectionPayload($categories), Response::HTTP_OK);

        return $this->handleView($view);
    }

    /**
     * TODO TODO TODO TODO TODO TODO TODO TODO TODO TODO TODO TODO TODO TODO TODO
     * @Post(summary="Update categories", description="Update categories")
     * @RequestBody(@MediaType(mediaType="application/json", @Schema(type="object",
     *      @Property(property="category_id", format="uuid", example="20cfd018-5eda-4c69-a9a2-014f3be6a7d0", type="object",
    *           @Property(property="language", type="string", example="en, fi, sw")
     *     ),
     * )))
     */
    public function updateCategories(UpdateCategoriesHandler $updateCategoriesHandler, UpdateCategoriesRequest $request): Response
    {
//        $command = new UpdateCategoriesCommand($request->getRequest()->toArray());
        $command = new UpdateCategoriesCommand(
            $request->get('categories'),
            $request->get('to_remove'),
            $request->get('to_backup')
        );
        $updateCategoriesHandler->handle($command);

        $view = $this->view(null, Response::HTTP_OK);

        return $this->handleView($view);
    }

    /**
     * @Post(summary="Update intiative", description="Update intiative")
     * @RequestBody(@MediaType(mediaType="multipart/form-data", @Schema(type="object",
     *      @Property(property="type", type="string"),
     *      @Property(property="category", type="string", format="uuid"),
     *      @Property(property="title", type="string"),
     *      @Property(property="description", type="string"),
     *      @Property(property="minimal_joined_people", type="integer"),
     *      @Property(property="location", type="string", format="lat[ANY_SEPARATOR_IGNORE_BRACKETS]lng"),
     *      @Property(property="location_name", type="string"),
     *      @Property(property="location_radius_value", type="number"),
     *      @Property(property="location_radius_unit", type="string", format="km"),
     *      @Property(property="date_start", type="string", format="date"),
     *      @Property(property="date_end", type="string", format="date"),
     *      @Property(property="images", type="array", format="binary", @OA\Items(type="string", format="binary")),
     *      @Property(property="images_to_remove", type="array", format="uuid", @OA\Items(type="string", format="uuid")),
     * )))
     */
    public function update(UpdateInitiativeHandler $updateInitiativeHandler, UpdateInitiativeRequest $request): Response
    {
        $fields = $request->getRequest()->request->all();
        $images = $request
            ->getRequest()
            ->files
            ->get(InitiativeContentModification::IMAGES);

        if ($images) {
            $fields[InitiativeContentModification::IMAGES] = $this->imageManager->prepare(...$images);
        }

        $command = new UpdateInitiativeCommand($request->get('initiative'), $fields);
        $initiative = $updateInitiativeHandler->handle($command);

        $view = $this->view(new InitiativePayload($initiative), Response::HTTP_OK);

        return $this->handleView($view);
    }
}
