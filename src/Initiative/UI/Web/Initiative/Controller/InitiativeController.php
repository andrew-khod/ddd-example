<?php

namespace App\Initiative\UI\Web\Initiative\Controller;

use App\Identity\Application\User\Exception\UserException;
use App\Identity\Infrastructure\User\Security\AuthenticatedCustomer;
use App\Initiative\Application\Category\Query\AllCategoriesListQuery;
use App\Initiative\Application\Comment\UseCase\Web\ReportComment\ReportCommentCommand;
use App\Initiative\Application\Comment\UseCase\Web\ReportComment\ReportCommentHandler;
use App\Initiative\Application\Initiative\Query\InitiativeByCriteriaQuery;
use App\Initiative\Application\Initiative\Query\InitiativeByFilterCriteria;
use App\Initiative\Application\Initiative\Query\InitiativeByIdCriteria;
use App\Initiative\Application\Initiative\UseCase\ArchiveInitiative\ArchiveInitiativeCommand;
use App\Initiative\Application\Initiative\UseCase\ArchiveInitiative\ArchiveInitiativeHandler;
use App\Initiative\Application\Initiative\UseCase\CommentInitiative\CommentInitiativeCommand;
use App\Initiative\Application\Initiative\UseCase\CommentInitiative\CommentInitiativeHandler;
use App\Initiative\Application\Initiative\UseCase\CreateInitiative\CreateInitiativeCommand;
use App\Initiative\Application\Initiative\UseCase\CreateInitiative\CreateInitiativeHandler;
use App\Initiative\Application\Initiative\UseCase\FavouriteInitiative\FavouriteInitiativeCommand;
use App\Initiative\Application\Initiative\UseCase\FavouriteInitiative\FavouriteInitiativeHandler;
use App\Initiative\Application\Initiative\UseCase\FollowInitiative\FollowInitiativeCommand;
use App\Initiative\Application\Initiative\UseCase\FollowInitiative\FollowInitiativeHandler;
use App\Initiative\Application\Initiative\UseCase\JoinInitiative\JoinInitiativeCommand;
use App\Initiative\Application\Initiative\UseCase\JoinInitiative\JoinInitiativeHandler;
use App\Initiative\Application\Initiative\UseCase\QuitInitiative\QuitInitiativeCommand;
use App\Initiative\Application\Initiative\UseCase\QuitInitiative\QuitInitiativeHandler;
use App\Initiative\Application\Initiative\UseCase\RemoveFavouriteInitiative\RemoveFavouriteInitiativeCommand;
use App\Initiative\Application\Initiative\UseCase\RemoveFavouriteInitiative\RemoveFavouriteInitiativeHandler;
use App\Initiative\Application\Initiative\UseCase\UnfollowInitiative\UnfollowInitiativeCommand;
use App\Initiative\Application\Initiative\UseCase\UnfollowInitiative\UnfollowInitiativeHandler;
use App\Initiative\Application\Initiative\UseCase\UpdateInitiative\InitiativeContentModification;
use App\Initiative\Application\Initiative\UseCase\UpdateInitiative\UpdateInitiativeCommand;
use App\Initiative\Application\Initiative\UseCase\UpdateInitiative\UpdateInitiativeHandler;
use App\Initiative\Application\Questionnaire\UseCase\AnswerQuestionnaires\AnswerQuestionnairesCommand;
use App\Initiative\Application\Questionnaire\UseCase\AnswerQuestionnaires\AnswerQuestionnairesHandler;
use App\Initiative\Domain\Initiative\InitiativeId;
use App\Initiative\Infrastructure\Initiative\Persistence\Doctrine\Query\InitiativeListQuery;
use App\Initiative\UI\Web\Comment\ReportCommentRequest;
use App\Initiative\UI\Web\Initiative\Request\AnswerQuestionnairesRequest;
use App\Initiative\UI\Web\Initiative\Request\CommentInitiativeRequest;
use App\Initiative\UI\Web\Initiative\Request\CreateInitiativeRequest;
use App\Initiative\UI\Web\Initiative\Request\FilterInitiativeListRequest;
use App\Initiative\UI\Web\Initiative\Request\UpdateInitiativeRequest;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use OpenApi\Annotations\Delete;
use OpenApi\Annotations\Get;
use OpenApi\Annotations\Items;
use OpenApi\Annotations\MediaType;
use OpenApi\Annotations\Post;
use OpenApi\Annotations\Property;
use OpenApi\Annotations\RequestBody;
use OpenApi\Annotations\Schema;
use OpenApi\Annotations\Tag;
use Symfony\Component\DependencyInjection\ContainerInterface;
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
     * @Post(summary="Create intiative", description="Create intiative")
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
     *      @Property(property="images", type="array", format="binary", @Items(type="string", format="binary")),
     * )))
     */
    public function create(CreateInitiativeHandler $createInitiativeHandler, CreateInitiativeRequest $request): Response
    {
        $type = $request->get('type');
        $category = $request->get('category');
        $title = $request->get('title');
        $description = $request->get('description');

        $minimalJoinedPeople = $request->get('minimal_joined_people');
        $location = $request->get('location');
        $locationName = $request->get('location_name');

        $locationRadiusValue = $request->get('location_radius_value');
        $locationRadiusUnit = $request->get('location_radius_unit');
        $questionnaires = $request->get('questionnaires');
        $dateStart = $request->get('date_start');
        $dateEnd = $request->get('date_end');
        $images = $request
            ->getRequest()
            ->files
            ->get('images');

        if ($images) {
            $images = $this->imageManager->prepare(...$images);
        }

//        $questionnaires = [
//            [
//                'type' => 'freetext',
//                'question' => 'tell whaddaya think about',
//            ],
//            [
//                'type' => 'single',
//                'question' => 'what do you think?',
//                'options' => [
//                    'like it',
//                    'dislike it',
//                    'aint care at all',
//                ],
//            ],
//            [
//                'type' => 'multi',
//                'question' => 'meal choice?',
//                'options' => [
//                    'pork',
//                    'hamburger',
//                    'eggs'
//                ],
//            ],
//        ];

        $command = new CreateInitiativeCommand(
            $type,
            $category,
            $title,
            $description,
            $dateStart,
            $dateEnd,
            $images,
            $minimalJoinedPeople,
            $location,
            $locationName,
            $locationRadiusValue,
            $locationRadiusUnit,
            $questionnaires
        );
        $initiative = $createInitiativeHandler->handle($command);

        $view = $this->view($initiative, Response::HTTP_CREATED);

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
     *      @Property(property="images", type="array", format="binary", @Items(type="string", format="binary")),
     *      @Property(property="images_to_remove", type="array", format="uuid", @Items(type="string", format="uuid")),
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

//        $fields['questionnaires'] = [
//            'create' => [
//                [
//                    'type' => 'single',
//                    'question' => 'NEW QUESTION',
//                    'options' => [
////                    'aa1',
//                        'bb2',
//                    ],
//                ]
//            ],
//            'update' => [
//                '4350c2ae-46eb-4c28-9b12-d0739b991e69' => [
//                    'question' => 'CHANGES QUESTION',
//                ],
//                '0d06bed3-953a-4de3-b4be-848cd83b4179' => [
//                    'type' => 'single',
//                    'options' => [
//                        'create' => [
//                            'new opt #1',
//                        ],
//                        'update' => [
//                            '31596390-6a4d-425b-9c70-fa6c63a6f6dd' => 'new opt txt #2',
//                        ],
//                        'delete' => [
//                            '46333e96-d727-4110-8389-f5d90372f339',
//                        ],
//                    ]
//                ]
//            ],
//            'delete' => [
//                'ccbeee93-f562-4d65-94fe-e1470bd84b59',
//            ],
////            [
////                'type' => 'freetext',
////                'question' => 'new question #1',
////            ],
////            [
////                'type' => 'single',
////                'question' => 'new question #2',
////                'options' => [
//////                    'aa1',
////                    'bb2',
////                ],
////            ],
////            [
////                'type' => 'multi',
////                'question' => 'meal choice?',
////                'options' => [
//////                    'pork',
////                    'hamburger2',
////                    'eggs'
////                ],
////            ],
//        ];

        $command = new UpdateInitiativeCommand($request->get('initiative'), $fields);
        $initiative = $updateInitiativeHandler->handle($command);

        $view = $this->view($initiative, Response::HTTP_OK);

        return $this->handleView($view);
    }

    /**
     * @Post(summary="Join initiative", description="Join initiative")
     */
    public function join(JoinInitiativeHandler $joinInitiativeHandler, Request $request): Response
    {
        $command = new JoinInitiativeCommand($request->get('initiative'));
        $initiative = $joinInitiativeHandler->handle($command);

        $view = $this->view($initiative, Response::HTTP_OK);

        return $this->handleView($view);
    }

    /**
     * @Post(summary="Follow initiative", description="Follow initiative")
     */
    public function follow(FollowInitiativeHandler $followInitiativeHandler, Request $request): Response
    {
        $command = new FollowInitiativeCommand($request->get('initiative'));
        $initiative = $followInitiativeHandler->handle($command);

        $view = $this->view($initiative, Response::HTTP_OK);

        return $this->handleView($view);
    }

    /**
     * @Delete(summary="Unfollow initiative", description="Unfollow initiative")
     */
    public function unfollow(UnfollowInitiativeHandler $unfollowInitiativeHandler, Request $request): Response
    {
        $command = new UnfollowInitiativeCommand($request->get('initiative'));
        $initiative = $unfollowInitiativeHandler->handle($command);

        $view = $this->view($initiative, Response::HTTP_OK);

        return $this->handleView($view);
    }

    /**
     * @Delete(summary="Quit initiative", description="Quit initiative")
     */
    public function quit(QuitInitiativeHandler $quitInitiativeHandler, Request $request): Response
    {
        $command = new QuitInitiativeCommand($request->get('initiative'));
        $initiative = $quitInitiativeHandler->handle($command);

        $view = $this->view($initiative, Response::HTTP_OK);

        return $this->handleView($view);
    }

    /**
     * @Post(summary="Archive initiative", description="Archive initiative")
     */
    public function archive(ArchiveInitiativeHandler $archiveInitiativeHandler, Request $request): Response
    {
        $command = new ArchiveInitiativeCommand($request->get('initiative'));
        $initiative = $archiveInitiativeHandler->handle($command);

        $view = $this->view($initiative, Response::HTTP_OK);

        return $this->handleView($view);
    }

    /**
     * @Post(summary="Mark initiative as favourite", description="Mark initiative as favourite")
     */
    public function favourite(FavouriteInitiativeHandler $favouriteInitiativeHandler, Request $request): Response
    {
        $command = new FavouriteInitiativeCommand($request->get('initiative'));
        $initiative = $favouriteInitiativeHandler->handle($command);

        $view = $this->view($initiative, Response::HTTP_OK);

        return $this->handleView($view);
    }

    /**
     * @Delete(summary="Remove initiative from favourites", description="Remove initiative from favourites")
     */
    public function removeFavourite(RemoveFavouriteInitiativeHandler $removeFavouriteInitiativeHandler, Request $request): Response
    {
        $command = new RemoveFavouriteInitiativeCommand($request->get('initiative'));
        $initiative = $removeFavouriteInitiativeHandler->handle($command);

        $view = $this->view($initiative, Response::HTTP_OK);

        return $this->handleView($view);
    }

    /**
     * @Post(summary="Comment initiative", description="Comment initiative")
     * @RequestBody(@MediaType(mediaType="application/json", @Schema(type="object",
     *      @Property(property="comment", type="string"),
     * )))
     */
    public function comment(CommentInitiativeHandler $commentInitiativeHandler, CommentInitiativeRequest $request): Response
    {
        $initiative = $request->get('initiative');
        $comment = $request->get('comment');

        $command = new CommentInitiativeCommand($initiative, $comment);
        $initiative = $commentInitiativeHandler->handle($command);

        $view = $this->view($initiative, Response::HTTP_CREATED);

        return $this->handleView($view);
    }

    /**
     * @Post(summary="Answer questionnaires", description="Answer questionnaires")
     * @RequestBody(@MediaType(mediaType="application/json", @Schema(type="object",
     *      @Property(property="answers", type="object"),
     * )))
     */
    public function answerQuestionnaires(AnswerQuestionnairesHandler $answerQuestionnairesHandler, AnswerQuestionnairesRequest $request): Response
    {
        $initiative = $request->get('initiative');
        $answers = $request->get('answers');

        $command = new AnswerQuestionnairesCommand($initiative, $answers);
        $answerQuestionnairesHandler->handle($command);

        $view = $this->view([], Response::HTTP_CREATED);

        return $this->handleView($view);
    }

    /**
     * @Post(summary="Report comment", description="Report comment")
     * @RequestBody(@MediaType(mediaType="application/json", @Schema(type="object",
     *      @Property(property="reason", type="string"),
     *      @Property(property="message", type="string"),
     *      @Property(property="url", type="string"),
     * )))
     */
    public function reportComment(ReportCommentHandler $reportCommentHandler, ReportCommentRequest $request): Response
    {
        $comment = $request->get('comment');
        $reason = $request->get('reason');
        $message = $request->get('message');
        $url = $request->get('url');

        $command = new ReportCommentCommand($comment, $reason, $message, $url);
        $reportCommentHandler->handle($command);

        $view = $this->view(null, Response::HTTP_CREATED);

        return $this->handleView($view);
    }

    /**
     * @Get(summary="Categories list", description="Categories list")
     */
    public function categories(AllCategoriesListQuery $allCategoriesListQuery): Response
    {
        $categories = $allCategoriesListQuery->query();

        $view = $this->view($categories, Response::HTTP_OK);

        return $this->handleView($view);
    }

    /**
     * @Get(summary="Get Initiative", description="Get Initiative")
     */
//    public function initiative(InitiativeByCriteriaQuery $initiativeByCriteriaQuery, AuthenticatedCustomer $authenticatedCustomer, Request $request): Response
    public function initiative(InitiativeByCriteriaQuery $initiativeByCriteriaQuery, ContainerInterface $container, Request $request): Response
    {
        //fixme dont throw exception for AuthenticatedCustomer if no user logged?
        $customer = null;

        try {
            $customer = $container->get(AuthenticatedCustomer::class);
        } catch (UserException $exception) {

        }

        $initiative = $initiativeByCriteriaQuery->queryOne(
            new InitiativeByIdCriteria(new InitiativeId($request->get('initiative')))
        );

        if ($initiative->isArchived() && (!$customer || !$initiative->isOwnedBy($customer->user()))) {
            $initiative = null;
        }

        $view = $this->view($initiative, Response::HTTP_OK);

        return $this->handleView($view);
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
        $criteria = new InitiativeByFilterCriteria($filter, $request->get('sort'), $request->get('page'), $security->getUser());
        $criteria->excludeArchived();

        if ($request->get('load_from_first_page')) {
            $criteria->setLoadFromFirstPage(true);
        }

        $initiatives = $initiativeByCriteriaQuery->queryMultiple($criteria);
//        $initiatives = $initiativeByCriteriaQuery->queryMultipleByPagination($criteria);
//        $initiatives = $initiativeByCriteriaQuery->queryMultiple($criteria);

        $view = $this->view(new InitiativeListPaginatedPayload($initiatives), Response::HTTP_OK);

        return $this->handleView($view);
    }

    /**
     * @Post(summary="Initiatives list on Map", description="Initiatives list on Map")
     */
//    public function initiatives(InitiativeListQuery $initiativeByCriteriaQuery, AuthenticatedCustomer $authenticatedCustomer, FilterInitiativeListRequest $request): Response
    public function initiativesOnMap(InitiativeListQuery $initiativeByCriteriaQuery, FilterInitiativeListRequest $request, Security $security): Response
//    public function initiatives()
    {
//        return [];
//
//        // TODO make separate endpoints like initiatives/archived, initiatives/active, initiatives/expired
        $filter = $request->get('filter') ?? [];
//        $criteria = (new InitiativeByFilterCriteria($filter))->excludeArchived();
        $criteria = new InitiativeByFilterCriteria($filter, $request->get('sort'), $request->get('page'), $security->getUser());
        $criteria->excludeArchived();

        $initiatives = $initiativeByCriteriaQuery->queryMultipleWithLocationsNoPagination($criteria);
//        $initiatives = $initiativeByCriteriaQuery->queryMultipleByPagination($criteria);
//        $initiatives = $initiativeByCriteriaQuery->queryMultiple($criteria);

        $view = $this->view($initiatives, Response::HTTP_OK);

        return $this->handleView($view);
    }
}
