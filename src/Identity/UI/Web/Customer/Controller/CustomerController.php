<?php

namespace App\Identity\UI\Web\Customer\Controller;

use App\Identity\Application\Customer\Query\CustomerNotificationsQuery;
use App\Identity\Application\Customer\UseCase\DeleteCustomer\DeleteCustomerHandler;
use App\Identity\Application\Customer\UseCase\HideAllNotifications\HideAllNotificationsHandler;
use App\Identity\Application\Customer\UseCase\MarkNotificationsAsRead\MarkNotificationsAsReadHandler;
use App\Identity\Application\Customer\UseCase\RecoverPassword\ConfirmPasswordRecoveryTokenHandler;
use App\Identity\Application\Customer\UseCase\RecoverPassword\RecoverPasswordHandler;
use App\Identity\Application\Customer\UseCase\SignUpCustomer\ActivateCustomerCommand;
use App\Identity\Application\Customer\UseCase\SignUpCustomer\ActivateCustomerHandler;
use App\Identity\Application\Customer\UseCase\SignUpCustomer\SignUpCustomerCommand;
use App\Identity\Application\Customer\UseCase\SignUpCustomer\SignUpCustomerHandler;
use App\Identity\Application\Customer\UseCase\SwitchLanguage\SwitchLanguageCommand;
use App\Identity\Application\Customer\UseCase\SwitchLanguage\SwitchLanguageHandler;
use App\Identity\Application\Customer\UseCase\UpdateCustomerProfile\UpdateCustomerProfileCommand;
use App\Identity\Application\Customer\UseCase\UpdateCustomerProfile\UpdateCustomerProfileHandler;
use App\Identity\Application\User\AuthenticatedCustomer;
use App\Identity\Application\User\UseCase\RecoverPassword\ConfirmPasswordRecoveryTokenCommand;
use App\Identity\Application\User\UseCase\RecoverPassword\RecoverPasswordCommand;
use App\Identity\Domain\Customer\CustomerTranslation;
use App\Identity\Infrastructure\Customer\Persistence\Doctrine\Query\CustomerForStatisticsQuery;
use App\Identity\UI\Dashboard\User\Request\RecoverPassword\PasswordRecoveryConfirmationRequest;
use App\Identity\UI\Dashboard\User\Request\RecoverPassword\PasswordRecoveryRequest;
use App\Identity\UI\Web\Customer\Request\ActivateCustomerRequest;
use App\Identity\UI\Web\Customer\Request\SignUpCustomerRequest;
use App\Identity\UI\Web\Customer\Request\SwitchLanguageRequest;
use App\Identity\UI\Web\Customer\Request\UpdateCustomerProfileRequest;
use App\Initiative\UI\Web\Initiative\Controller\ImageManager;
use FOS\RestBundle\Controller\AbstractFOSRestController;
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

/**
 * @Tag(name="Identity")
 */
class CustomerController extends AbstractFOSRestController
{
    private ImageManager $imageManager;

    public function __construct()
    {
        $this->imageManager = new ImageManager();
    }

    /**
     * @Get(summary="Authenticated customer info", description="Authenticated customer info")
     */
    public function selfProfile(AuthenticatedCustomer $authenticatedCustomer, CustomerForStatisticsQuery $customerForStatisticsQuery): Response
//    public function selfProfile()
    {
        $customer = $customerForStatisticsQuery->query($authenticatedCustomer->user()->id());

        // todo don't render there initiatives. we need there just a customer's data, use separate endpoint for customers profile
        $view = $this->view($customer, Response::HTTP_OK);

        return $this->handleView($view);
    }

    //todo move to NotificationsController or Bounded Context/module
    /**
     * @Get(summary="Authenticated customer notifications", description="Authenticated customer notifications")
     */
    public function notifications(AuthenticatedCustomer $authenticatedCustomer, CustomerNotificationsQuery $customerNotificationsQuery): Response
    {
        $notifications = $customerNotificationsQuery->queryAsPayload($authenticatedCustomer->user());

        $view = $this->view(new NotificationsPayload($notifications), Response::HTTP_OK);

        return $this->handleView($view);
    }

    //todo move to NotificationsController or Bounded Context/module
    /**
     * @Post(summary="Mark all notifications as read", description="Mark all notifications as read")
     */
    public function markAllNotificationsAsRead(MarkNotificationsAsReadHandler $markNotificationsAsReadHandler, Request $request): Response
    {
        $markNotificationsAsReadHandler->handle();

        $view = $this->view(null, Response::HTTP_OK);

        return $this->handleView($view);
    }

    //todo move to NotificationsController or Bounded Context/module
    /**
     * @Post(summary="Hide all notifications", description="Hide all notifications")
     */
    public function hideAllNotifications(HideAllNotificationsHandler $hideAllNotificationsHandler, Request $request): Response
    {
        $hideAllNotificationsHandler->handle();

        $view = $this->view(null, Response::HTTP_OK);

        return $this->handleView($view);
    }

    /**
     * @Post(summary="Update profile of authenticated customer", description="Update profile of authenticated customer")
     * @RequestBody(@MediaType(mediaType="application/json", @Schema(type="object",
     *      @Property(property="username", type="string"),
     *      @Property(property="birthday", type="string", format="date"),
     *      @Property(property="gender", type="string"),
     *      @Property(property="postal", type="string"),
     *      @Property(property="new_password", type="string"),
     * )), @MediaType(mediaType="multipart/form-data", @Schema(type="object",
     *      @Property(property="photo", format="binary", type="string"),
     * )))
     */
    public function updateProfile(UpdateCustomerProfileHandler $updateCustomerProfileHandler, UpdateCustomerProfileRequest $request, CustomerForStatisticsQuery $customerForStatisticsQuery): Response
    {
        $images = $request->getRequest()->files->all();

        if (count($images)) {
            foreach ($images as $i => $image) {
                $images[$i] = $this->imageManager->prepare($image);
            }
        }

        $fields = array_merge(
            $request->getRequest()->request->all(),
            $images,
        );

        $command = new UpdateCustomerProfileCommand($fields);
        $customer = $updateCustomerProfileHandler->handle($command);

        $view = $this->view($customerForStatisticsQuery->query($customer->id()), Response::HTTP_OK);

        return $this->handleView($view);
    }

    /**
     * @Delete(summary="Remove profile of authenticated customer", description="Remove profile of authenticated customer")
     */
    public function deleteCustomer(DeleteCustomerHandler $deleteCustomerHandler): Response
    {
        $deleteCustomerHandler->handle();

        $view = $this->view([CustomerTranslation::ACCOUNT_DELETED], Response::HTTP_OK);

        return $this->handleView($view);
    }

    /**
     * @Post(summary="Sign up", description="Sign up")
     * @RequestBody(@MediaType(mediaType="application/json", @Schema(type="object",
     *      @Property(property="email", type="string"),
     * )))
     */
    public function signUp(SignUpCustomerHandler $signUpCustomerHandler, SignUpCustomerRequest $request): Response
    {
        $email = $request->get('email');

        $command = new SignUpCustomerCommand($email);
        $customer = $signUpCustomerHandler->handle($command);

        $view = $this->view($customer, Response::HTTP_CREATED);

        return $this->handleView($view);
    }

    /**
     * @Post(summary="Switch active language", description="Switch active language")
     * @RequestBody(@MediaType(mediaType="application/json", @Schema(type="object",
     *      @Property(property="language", type="string"),
     * )))
     */
    public function switchLanguage(SwitchLanguageHandler $switchLanguageHandler, SwitchLanguageRequest $request): Response
    {
        $email = $request->get('language');

        $command = new SwitchLanguageCommand($email);
        $switchLanguageHandler->handle($command);

        $view = $this->view(null, Response::HTTP_OK);

        return $this->handleView($view);
    }

    /**
     * @Post(summary="Activate account", description="Activate account")
     * @RequestBody(@MediaType(mediaType="application/json", @Schema(type="object",
     *      @Property(property="token", type="string"),
     *      @Property(property="password", type="string"),
     *      @Property(property="username", type="string"),
     * )))
     */
    public function activateAccount(ActivateCustomerHandler $activateCustomerHandler, ActivateCustomerRequest $request): Response
    {
        $token = $request->get('token');
        $password = $request->get('password');
        $username = $request->get('username');

        $command = new ActivateCustomerCommand($token, $password, $username);
        $customer = $activateCustomerHandler->handle($command);

        $view = $this->view($customer, Response::HTTP_OK);

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
}
