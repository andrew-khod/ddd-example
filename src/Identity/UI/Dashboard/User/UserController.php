<?php

namespace App\Identity\UI\Dashboard\User;

use App\Identity\Application\User\AuthenticatedUser;
use App\Identity\Application\User\UseCase\ResendUserActivation\ResendUserActivationCommand;
use App\Identity\Application\User\UseCase\ResendUserActivation\ResendUserActivationHandler;
use App\Identity\Application\User\UseCase\SwitchCompany\SwitchCompanyCommand;
use App\Identity\Application\User\UseCase\SwitchCompany\SwitchCompanyHandler;
use App\Identity\Application\User\UseCase\UpdateUser\UpdateUserCommand;
use App\Identity\Application\User\UseCase\UpdateUser\UpdateUserHandler;
use App\Identity\Infrastructure\User\Persistence\Doctrine\Query\AdminListQuery;
use App\Identity\UI\Dashboard\User\Request\UpdateUserRequest;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use OpenApi\Annotations\Get;
use OpenApi\Annotations\Items;
use OpenApi\Annotations\Parameter;
use OpenApi\Annotations\Post;
use OpenApi\Annotations\Schema;
use OpenApi\Annotations\Tag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Tag(name="Admins",description="Manage admins")
 */
class UserController extends AbstractFOSRestController
{
    /**
     * @Get(summary="Admins list", description="Admins list")
     */
//    public function list(UserByCriteriaQuery $userByCriteriaQuery, AllUserByCompanyCriteriaV2 $criteria): Response
//    public function list(AdminListQuery $userByCriteriaQuery, AllUserByCompanyCriteriaV2 $criteria): Response
    public function list(AdminListQuery $userByCriteriaQuery, AuthenticatedUser $authenticatedUser): Response
    {
//        $criteria = new AllUserByCompanyCriteriaV2();
//        $admins = $userByCriteriaQuery->queryMultipleV2($criteria);
        if ($authenticatedUser->user()->isSuperAdmin()) {
            $userByCriteriaQuery->includeSuperadmins();
        } else {
            $userByCriteriaQuery->excludeSuperadmins();
        }

        $admins = $userByCriteriaQuery->query();

        $view = $this->view($admins, Response::HTTP_OK);

        return $this->handleView($view);
    }

    /**
     * @Post(summary="Edit admin", description="Edit admin")
     * @Parameter(
     *     name="username",
     *     in="query",
     *     description="Admin username",
     *     @Schema(type="string")
     * )
     * @Parameter(
     *     name="is_superadmin",
     *     in="query",
     *     description="Make user superadmin",
     *     @Schema(type="boolean")
     * )
     * @Parameter(
     *     name="permissions",
     *     in="query",
     *     description="Permissions ids",
     *     @Schema(type="array", @Items(type="string", format="uuid"))
     * )
     */
    public function edit(UpdateUserHandler $updateUserHandler, UpdateUserRequest $request): Response
    {
        $permissions = $request->get('permissions');
        $user = $request->get('admin');
        $username = $request->get('username');
        $isSuperAdmin = $request->get('is_superadmin');

        $command = new UpdateUserCommand($user, $permissions, $username, $isSuperAdmin);
        $admin = $updateUserHandler->handle($command);

        $view = $this->view($admin, Response::HTTP_OK);

        return $this->handleView($view);
    }

    /**
     * @Post(summary="Switch company", description="Switch company")
     */
    public function switchCompany(SwitchCompanyHandler $switchCompanyHandler, Request $request): Response
    {
        $company = $request->get('company');

        $command = new SwitchCompanyCommand($company);
        $switchCompanyHandler->handle($command);

        $view = $this->view(null, Response::HTTP_OK);

        return $this->handleView($view);
    }
    /**
     * @Post(summary="Resend activation for User", description="Resend activation for User")
     */
    public function resendActivation(ResendUserActivationHandler $resendUserActivationHandler, Request $request): Response
    {
        $resendUserActivationHandler->handle(new ResendUserActivationCommand($request->get('user')));

        $view = $this->view(null, Response::HTTP_CREATED);

        return $this->handleView($view);
    }
}
