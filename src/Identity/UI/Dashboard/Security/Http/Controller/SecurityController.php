<?php

namespace App\Identity\UI\Dashboard\Security\Http\Controller;

use App\Identity\Application\AuthVendor\Query\AuthVendorByCriteriaQuery;
use App\Identity\Application\AuthVendor\Query\AuthVendorByUserIdCriteria;
use App\Identity\Application\Permission\Query\PermissionConfigurationQuery;
use App\Identity\Application\User\UseCase\CreateUser\CreateUserCommand;
use App\Identity\Application\User\UseCase\CreateUser\CreateUserHandler;
use App\Identity\Application\User\UseCase\DeleteUser\DeleteUserCommand;
use App\Identity\Application\User\UseCase\DeleteUser\DeleteUserHandler;
use App\Identity\Application\User\UseCase\RecoverPassword\ConfirmPasswordRecoveryTokenCommand;
use App\Identity\Application\User\UseCase\RecoverPassword\ConfirmPasswordRecoveryTokenHandler;
use App\Identity\Application\User\UseCase\RecoverPassword\RecoverPasswordCommand;
use App\Identity\Application\User\UseCase\RecoverPassword\RecoverPasswordHandler;
use App\Identity\Domain\Customer\CustomerTranslation;
use App\Identity\Infrastructure\User\Security\AuthenticatedUser;
use App\Identity\UI\Dashboard\User\Request\CreateUserRequest;
use App\Identity\UI\Dashboard\User\Request\RecoverPassword\PasswordRecoveryConfirmationRequest;
use App\Identity\UI\Dashboard\User\Request\RecoverPassword\PasswordRecoveryRequest;
use App\Shared\Application\AssignedToCompanyLanguagesQuery;
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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Tag(name="Identity")
 */
class SecurityController extends AbstractFOSRestController
{
    /**
     * @Get(summary="Permissions list", description="Permissions list")
     */
    public function permissions(PermissionConfigurationQuery $permissionConfigurationQuery): Response
    {
        $permissionConfiguration = $permissionConfigurationQuery->query();

        $view = $this->view($permissionConfiguration, Response::HTTP_OK);

        return $this->handleView($view);
    }

    /**
     * @Post(summary="Create admin", description="Create admin")
     * @RequestBody(@MediaType(mediaType="application/json", @Schema(type="object",
     *      @Property(property="email", type="string"),
     *      @Property(property="username", type="string"),
     *      @Property(property="permissions", type="array", @Items(type="string", format="uuid")),
     * )))
     */
    public function createUser(CreateUserHandler $createUserHandler, CreateUserRequest $request): Response
    {
        $email = $request->get('email');
        $permissions = $request->get('permissions');
        $username = $request->get('username');

        $command = new CreateUserCommand($email, $permissions, $username);
        $user = $createUserHandler->handle($command);

        $view = $this->view($user, Response::HTTP_CREATED);

        return $this->handleView($view);
    }

    /**
     * @Delete(summary="Ban admin", description="Ban admin")
     */
    public function deleteUser(DeleteUserHandler $deleteUserHandler, Request $request): Response
    {
        $user = $request->get('user');

        $command = new DeleteUserCommand($user);
        $deleteUserHandler->handle($command);

        $view = $this->view([CustomerTranslation::ACCOUNT_DELETED], Response::HTTP_OK);

        return $this->handleView($view);
    }

    /**
     * @Get(summary="Current user info", description="Current user info")
     */
    public function user(AuthenticatedUser $authenticatedUser, AssignedToCompanyLanguagesQuery $allLanguageQuery): Response
//    public function user(): Response
    {
        $view = $this->view(new UserPayload($authenticatedUser->user(), $allLanguageQuery->query()), Response::HTTP_OK);
//        $view = $this->view(null, Response::HTTP_OK);

        return $this->handleView($view);
    }

    /**
     * @Post(summary="Request password recovery", description="Request password recovery")
     * @RequestBody(@MediaType(mediaType="application/json", @Schema(type="object",
     *      @Property(property="email", type="string"),
     * )))
     */
    public function passwordRecoveryTokenVerification(RecoverPasswordHandler $recoverPasswordHandler, PasswordRecoveryRequest $request): Response
    {
        $email = $request->get('email');

        $command = new RecoverPasswordCommand($email);
        $recoverPasswordHandler->handle($command);

        $view = $this->view(CustomerTranslation::PASSWORD_RECOVERY_TOKEN_SENT, Response::HTTP_ACCEPTED);

        return $this->handleView($view);
    }

    /**
     * @Post(summary="Verify password recovery token and setup password", description="Verify password recovery token and setup password")
     * @RequestBody(@MediaType(mediaType="application/json", @Schema(type="object",
     *      @Property(property="token", type="string"),
     *      @Property(property="password", type="string"),
     * )))
     */
    public function passwordRecoveryTokenConfirmation(ConfirmPasswordRecoveryTokenHandler $confirmPasswordRecoveryTokenHandler, PasswordRecoveryConfirmationRequest $request): Response
    {
        $token = $request->get('token');
        $password = $request->get('password');

        $command = new ConfirmPasswordRecoveryTokenCommand($token, $password);
        $confirmPasswordRecoveryTokenHandler->handle($command);

        $view = $this->view(CustomerTranslation::PASSWORD_CHANGED, Response::HTTP_OK);

        return $this->handleView($view);
    }

    /**
     * @Get(summary="List of linked auth providers", description="List of linked auth providers")
     */
    public function allowedAuthVendors(AuthenticatedUser $authenticatedUser, AuthVendorByCriteriaQuery $authVendorByCriteriaQuery): Response
    {
        $user = $authenticatedUser->user();
        $criteria = new AuthVendorByUserIdCriteria($user->id());
        $vendors = $authVendorByCriteriaQuery->queryMultiple($criteria);

        $view = $this->view($vendors, Response::HTTP_OK);

        return $this->handleView($view);
    }
}
