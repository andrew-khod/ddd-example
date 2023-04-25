<?php

namespace App\Identity\UI\Web\Customer\Controller;

use App\Identity\Application\Customer\UseCase\SignInCustomerByGoogleAuth\SignInCustomerByGoogleAuthCommand;
use App\Identity\Application\Customer\UseCase\SignInCustomerByGoogleAuth\SignInCustomerByGoogleAuthHandler;
use App\Identity\UI\Dashboard\Security\Http\Request\LinkGoogleAuth\LinkGoogleAuthRequest;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Http\Authentication\AuthenticationSuccessHandler;
use OpenApi\Annotations\MediaType;
use OpenApi\Annotations\Post;
use OpenApi\Annotations\Property;
use OpenApi\Annotations\RequestBody;
use OpenApi\Annotations\Schema;
use OpenApi\Annotations\Tag;

/**
 * @Tag(name="Identity")
 */
class GoogleOauthController extends AbstractFOSRestController
{
    /**
     * @Post(summary="Sign in by Google", description="Sign in by Google")
     * @RequestBody(@MediaType(mediaType="application/json", @Schema(type="object",
     *      @Property(property="code", type="string"),
     * )))
     */
    public function verification(AuthenticationSuccessHandler $authenticationSuccessHandler,
                                 SignInCustomerByGoogleAuthHandler $signInCustomerByGoogleAuthHandler,
                                 LinkGoogleAuthRequest $request)
    {
        $code = $request->get('code');

        $command = new SignInCustomerByGoogleAuthCommand($code);
        $customer = $signInCustomerByGoogleAuthHandler->handle($command);

        return $authenticationSuccessHandler->handleAuthenticationSuccess($customer);
    }
}
